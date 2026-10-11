<?php

namespace App\Livewire;

use App\Mail\ContactMessageReceived;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:190')]
    public string $email = '';

    #[Validate('required|string|min:5|max:3000')]
    public string $body = '';

    public string $website = '';

    public bool $sent = false;

    public string $locale = '';

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function hydrate(): void
    {
        if ($this->locale !== '' && array_key_exists($this->locale, config('app.locales'))) {
            app()->setLocale($this->locale);
        }
    }

    protected function messages(): array
    {
        return [
            'required' => __('A mező kitöltése kötelező.'),
            'email' => __('Adj meg érvényes e-mail címet.'),
            'min' => __('A szöveg túl rövid.'),
            'max' => __('A szöveg túl hosszú.'),
        ];
    }

    public function submit(): void
    {
        // Honeypot: botok kitöltik a rejtett mezőt
        if ($this->website !== '') {
            $this->sent = true;

            return;
        }

        $this->validate();

        $message = Message::create($this->only(['name', 'email', 'body']));

        try {
            Mail::to(config('mail.contact_to'))->send(new ContactMessageReceived($message));
        } catch (\Throwable $e) {
            report($e);
        }

        $this->reset(['name', 'email', 'body']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
