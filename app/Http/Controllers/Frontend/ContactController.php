<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:general,partnership,volunteering,donation,media',
            'message' => 'required|string|min:10|max:2000',
            'privacy' => 'required|accepted',
        ], [
            'name.required' => __('Le nom est obligatoire.'),
            'email.required' => __('L\'adresse e-mail est obligatoire.'),
            'email.email' => __('L\'adresse e-mail doit être valide.'),
            'subject.required' => __('Veuillez choisir un sujet.'),
            'subject.in' => __('Le sujet sélectionné n\'est pas valide.'),
            'message.required' => __('Le message est obligatoire.'),
            'message.min' => __('Le message doit contenir au moins 10 caractères.'),
            'message.max' => __('Le message ne peut pas dépasser 2000 caractères.'),
            'privacy.required' => __('Vous devez accepter la politique de confidentialité.'),
            'privacy.accepted' => __('Vous devez accepter la politique de confidentialité.'),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        
        // Mapping des sujets pour l'affichage
        $subjects = [
            'general' => __('Demande générale'),
            'partnership' => __('Partenariat'),
            'volunteering' => __('Bénévolat'),
            'donation' => __('Don/Soutien'),
            'media' => __('Média/Presse'),
        ];

        try {
            // Ici vous pouvez envoyer l'email
            // Pour l'instant, on simule l'envoi
            
            // Mail::send('emails.contact', $data, function($message) use ($data) {
            //     $message->to(config('mail.contact_email', 'info@nama.org'))
            //             ->subject('Nouveau message de contact - ' . $subjects[$data['subject']]);
            //     $message->replyTo($data['email'], $data['name']);
            // });

            return back()->with('success', __('Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'));
            
        } catch (\Exception $e) {
            return back()
                ->with('error', __('Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'))
                ->withInput();
        }
    }
}