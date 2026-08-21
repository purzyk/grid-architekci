<?php
/**
 * One-off seed script: creates the Kontakt contact form in Contact Form 7,
 * styled with the mock's actual computed field/button values (the design
 * system's .field/.input/.btn classes aren't in our CSS — these are their
 * resolved values, copied as literal Tailwind utilities instead).
 *
 * Contact Form 7 itself is a third-party plugin and isn't tracked in git
 * (same convention as every other third-party plugin — see .gitignore).
 * On a fresh checkout, install it first:
 *   ddev wp plugin install contact-form-7 --activate
 * Then run this script:
 *   ddev wp eval-file wordpress/wp-content/seed-content/seed-contact-form.php
 *
 * Mail delivery (SMTP) isn't configured yet — see SPEC.md's "Open technical
 * decisions" — so submissions validate and show a success message but
 * wp_mail() has nowhere real to send through until that's set up.
 */

if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
	echo "Contact Form 7 not active — aborting.\n";
	return;
}

// Re-runnable: update the existing form in place if one already exists,
// rather than delete-and-recreate, so its post ID (and therefore the
// [contact-form-7 id="..."] shortcode in kontakt-content.html) stays
// stable across re-runs instead of changing every time this script runs.
$existing = get_posts( array(
	'post_type'      => 'wpcf7_contact_form',
	'title'          => 'Kontakt',
	'posts_per_page' => 1,
	'post_status'    => 'any',
	'fields'         => 'ids',
) );

// CF7's own tag syntax is delimited by [ ], so Tailwind's arbitrary-value
// classes (which also contain literal brackets, e.g. min-h-[36px]) can't
// go inside a tag's class: option — CF7's tag parser hits the inner ]
// and treats the tag as malformed, rendering it as literal text instead
// of a field. Every CF7 field already carries the fixed classes
// wpcf7-form-control (all fields) / wpcf7-submit (the button) regardless
// of any class: option, so styling is applied from the wrapping div in
// kontakt-content.html via those instead, and no class: option is used
// here at all.
$label_class = 'block text-[12px] mb-[5px] uppercase tracking-[0.14em] text-ink/70';

// Each field is written on a single line (label immediately followed by
// the tag, no linebreak between them) — WordPress's wpautop(), which runs
// on the form template same as any post content, turns a linebreak inside
// a block like this into a stray <p>/<br> pair around the label, adding
// unwanted vertical space and making the whole field look oversized even
// though the input itself is sized correctly.
//
// Honeypot: a field real visitors never see or tab into (off-screen, not
// display:none — some bots specifically skip display:none fields since
// that's the textbook honeypot tell) but that unsophisticated form-filling
// bots reliably fill in anyway, named to look worth filling. grid-core.php's
// wpcf7_spam hook rejects the submission if it's non-empty.
$honeypot = '<div class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">[text your-website tabindex:-1 autocomplete:off]</div>';

$form = <<<FORM
<div><label for="your-name" class="{$label_class}">Imię i nazwisko</label>[text* your-name id:your-name placeholder "Jan Kowalski"]</div>

<div><label for="your-email" class="{$label_class}">E-mail</label>[email* your-email id:your-email placeholder "jan@example.com"]</div>

<div><label for="your-phone" class="{$label_class}">Telefon (opcjonalnie)</label>[tel your-phone id:your-phone placeholder "+48"]</div>

<div><label for="your-location" class="{$label_class}">Lokalizacja inwestycji</label>[text your-location id:your-location placeholder "Miasto, gmina"]</div>

<div><label for="your-message" class="{$label_class}">O czym myślisz</label>[textarea your-message id:your-message rows:5 placeholder "Kilka zdań o inwestycji, skali i terminie."]</div>

{$honeypot}

[submit "Wyślij zapytanie"]
FORM;

$mail = array(
	'subject'            => 'Nowe zapytanie ze strony — [your-name]',
	'sender'             => 'GRID Architekci <wordpress@grid.net.pl>',
	'recipient'          => 'info@grid.net.pl',
	'additional_headers' => "Reply-To: [your-email]",
	'body'               => "Imię i nazwisko: [your-name]\nE-mail: [your-email]\nTelefon: [your-phone]\nLokalizacja inwestycji: [your-location]\n\nO czym myśli:\n[your-message]\n\n--\nWysłane z formularza kontaktowego na grid.net.pl",
	'use_html'           => false,
	'exclude_blank'      => true,
);

$messages = array(
	'invalid_required' => 'To pole jest wymagane.',
	'invalid_too_long'  => 'Tekst jest za długi.',
	'invalid_too_short' => 'Tekst jest za krótki.',
	'validation_error'  => 'Sprawdź poprawność wypełnionych pól.',
	'mail_sent_ok'      => 'Dziękujemy, wiadomość została wysłana.',
	'mail_sent_ng'      => 'Nie udało się wysłać wiadomości. Spróbuj ponownie później.',
	'upload_failed'     => 'Nie udało się przesłać pliku.',
	'invalid_email'     => 'Podaj poprawny adres e-mail.',
	'spam'              => 'Wiadomość została odrzucona jako spam.',
	'accept_terms'      => 'Musisz zaakceptować warunki, aby wysłać wiadomość.',
	'invalid_date'      => 'Podaj poprawną datę.',
	'date_too_early'    => 'Data jest zbyt wczesna.',
	'date_too_late'     => 'Data jest zbyt późna.',
	'upload_failed_php_error' => 'Błąd podczas przesyłania pliku.',
	'invalid_number'    => 'Podaj poprawną liczbę.',
	'number_too_small'  => 'Liczba jest za mała.',
	'number_too_large'  => 'Liczba jest za duża.',
	'quiz_answer_not_correct' => 'Niepoprawna odpowiedź.',
	'invalid_url'       => 'Podaj poprawny adres URL.',
	'invalid_tel'       => 'Podaj poprawny numer telefonu.',
);

$contact_form = ! empty( $existing )
	? WPCF7_ContactForm::get_instance( $existing[0] )
	: WPCF7_ContactForm::get_template( array( 'title' => 'Kontakt' ) );
$contact_form->set_properties( array(
	'form'     => $form,
	'mail'     => array_merge( $contact_form->prop( 'mail' ), $mail ),
	'messages' => array_merge( $contact_form->prop( 'messages' ), $messages ),
) );
$contact_form->save();

echo 'Created Contact Form 7 form, ID ' . $contact_form->id() . ". Shortcode: [contact-form-7 id=\"" . $contact_form->id() . "\" title=\"Kontakt\"]\n";
