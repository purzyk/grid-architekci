# WPML — plan wdrożenia (PL + EN)

Status: kroki 1–4 zrobione (backup, instalacja WPML+dodatków, konfiguracja
język PL/EN + podkatalog, kod — patrz "Kolejność prac" niżej). Dalej: kroki
5–8 (tryby tłumaczenia per CPT/pole/taksonomia, punkt F, testowe
tłumaczenie, reszta treści).

## Decyzje architektoniczne

- **Format URL: podkatalog** (`grid.net.pl/en/...`), nie subdomena i nie
  osobna domena. Powody: domena ma lata historii/backlinków, które podkatalog
  dziedziczy od razu; stara strona (`grid.net.pl`, sprzed migracji) już
  używała tego formatu (`language_negotiation_type = 2` w jej bazie); zero
  dodatkowej infrastruktury (SSL/vhost/WAF/cache) do utrzymania jak przy
  subdomenie; jedna spójna usługa GA4.
- **Statyczne pliki `.html` szablonów (theme) NIE są przepisywane na klasyczny
  PHP.** Fragmenty z twardym tekstem (patrz niżej) zamieniamy na małe
  dynamiczne bloki z `render.php`, dokładnie ten sam wzorzec co
  `site-header`/`cookie-consent` — żadnego nowego mechanizmu.
- **Stringi renderowane wyłącznie w JS (Interactivity API) nie są tłumaczone
  w JS.** Oba warianty trafiają do PHP (`__()`), przekazywane do `view.js`
  przez `wp_interactivity_data_wp_context()`; JS tylko wybiera który pokazać.
- **Twarde linki do polskich slugów** (np. `home_url('/o-nas/')`) zamieniamy
  na helper w `grid-core.php` (`grid_translated_url( $slug )`), który przez
  `apply_filters('wpml_object_id', ...)` zwraca `get_permalink()` właściwej
  wersji językowej, z fallbackiem do obecnego zachowania gdy WPML nieaktywne.

## Audyt: co gdzie trzeba zmienić

### A. Zwykłe hardkodowane stringi w PHP — owinąć w `__()`

| Plik | Stringi |
|---|---|
| `grid-blocks/src/site-header/render.php` | "Projekty", "O nas", "Osiągnięcia", "Kontakt", `aria-label="Tryb ciemny"`, `aria-label="Menu"` |
| `grid-blocks/src/project-grid/render.php` | "Wszystkie", "Pokaż więcej projektów" (initial state) |
| `grid-blocks/src/project-detail/render.php` | "Rok", "Status" (etykiety w gridzie metryk) |
| `grid-blocks/src/project-nav/render.php` | "Poprzedni", "Następny", "Wszystkie projekty" |
| `grid-blocks/src/awards-table/render.php` | "Rok", "Konkurs / nagroda", "Projekt", "Wynik" (nagłówki tabeli) |
| `grid-blocks/src/cookie-consent/render.php` | cała treść bannera cookies, link, "Odrzucam", "Akceptuję" |

### B. String tylko w skompilowanym JS

`grid-blocks/src/project-grid/view.js` — `'Pokaż mniej'` (alternatywny stan
po kliknięciu, nigdy nie renderowany server-side). Fix: przenieść obie
etykiety do `$context` w `render.php` (`__()`-wrapped), `view.js` tylko
wybiera `state.isExpanded ? context.lessLabel : context.moreLabel`.

### C. Statyczne pliki `.html` — nowe małe bloki dynamiczne

- `themes/grid/parts/footer.html` — "Kulisy naszej pracy, postępy na
  budowach i świeże realizacje.", "Polityka prywatności i regulamin",
  "Ustawienia cookies", "Wdrożenie:", "Copyright: Grid architekci 2026".
  Plan: nowy blok (np. `grid/site-footer-info`) przejmuje tę część stopki
  (social links + adres + copyright bar), `render.php` z `__()`.
- `themes/grid/templates/404.html` — "Błąd 404", "Strony nie znaleziono",
  opis, "Wróć na stronę główną". Plan: nowy mały blok (np. `grid/not-found`)
  z `render.php`.

### D. Twarde linki do polskich slugów

- `site-header/render.php`: `home_url('/o-nas/')`, `home_url('/osiagniecia/')`,
  `home_url('/kontakt/')`
- `cookie-consent/render.php`: `home_url('/polityka-prywatnosci-i-plikow-cookies-oraz-regulamin/')`
- `project-nav/render.php`: `home_url('/')` — to zostaje bez zmian, strona
  główna jest tym samym adresem w każdym języku (root podkatalogu).

Fix: helper `grid_translated_url( $slug )` w `grid-core.php`.

### E. NIE wymaga zmian w kodzie — to już jest treść, nie kod

- Domyślne wartości atrybutów bloków (`label`/`note` w `team-grid`,
  `publications-grid`, `current-projects`) — żyją w treści posta,
  tłumaczalne normalnie w edytorze bloków.
- Cała treść stron (Home/O nas/Kontakt/Osiągnięcia) z `seed-content/` —
  zwykła treść WP, tłumaczalna przez WPML jak każdy inny post.
- Komunikaty walidacyjne Contact Form 7 (`seed-contact-form.php`
  `$messages`) — obsłuży dodatek WPML Multilingual for CF7.
- Wiersze repeatera `metryka` (etykiety wpisywane przez klienta w ACF) —
  to dane wpisane przez użytkownika, nie kod.

### F. Do sprawdzenia przy konfiguracji (nie string, ale wpłynie na działanie)

- `WP_Query`/`get_posts()` z `post_type => 'projekt'` w kilku blokach
  (`project-nav`, `team-grid`, `awards-table`, `featured-awards`,
  `publications-grid`) nie ustawia jawnie `suppress_filters` — sprawdzić czy
  WPML poprawnie filtruje po aktualnym języku, czy zacznie mieszać wpisy z
  obu języków. `project-nav` jest tu najbardziej wrażliwy (chodzi po
  `menu_order` między projektami) — **wymaga realnej dwujęzycznej treści do
  sprawdzenia, więc odłożone do kroku 6/7, kiedy będzie co testować.**
- ✅ **Zrobione** (przy okazji kroku 4, bo to ten sam bezpieczny wzorzec co
  `grid_translated_url()`): każde miejsce czytające wprost ID posta
  `projekt` z ACF i używające go bez filtrowania językowego zostało
  przepuszczone przez `apply_filters('wpml_object_id', $id, 'projekt',
  true)`. Dotyczy trzech miejsc (audyt przy pisaniu kodu znalazł dwa więcej
  niż pierwotnie tu spisane):
  - `current-projects/render.php` — ID z ACF Options (`aktualne_projekty`).
  - `awards-table/render.php` i `featured-awards/render.php` — oba czytają
    `projekt_powiazany` (pole relationship na CPT `nagroda`).

## Kolejność prac (do ustalenia szczegółowo przy starcie)

1. ✅ Backup bazy produkcyjnej (świeży, tuż przed startem).
2. ✅ Instalacja WPML + ACF Multilingual + WPML SEO + WPML dla CF7 (Piotr).
3. ✅ Konfiguracja: język domyślny PL, dodatkowy EN, format URL = podkatalog.
   Przy okazji: prawdziwy przełącznik PL/EN w nagłówku (dopasowany do
   makiety), domyślny widget WPML w stopce ukryty przez CSS.
4. ✅ Kod: punkty A–D powyżej (stringi + nowe bloki + helper URL). Nowe
   bloki `grid/site-footer-info` i `grid/not-found` zastąpiły statyczny
   tekst w `footer.html`/`404.html`. Zweryfikowane na produkcji
   (Playwright): nawigacja, stopka, 404, metryki projektu, tabela
   osiągnięć, toggle "Pokaż więcej/mniej", przycisk ustawień cookies.
5. ✅ Konfiguracja trybów tłumaczenia per custom post type
   (`projekt`/`zespol`/`nagroda`/`publikacja`) i per pole ACF, oraz per
   taksonomia (`projekt_kategoria`/`projekt_status`). Uwaga: pierwsza próba
   ustawienia tego przez Piotra w GUI nie zapisała się poprawnie — wszystkie
   4 CPT i obie taksonomie faktycznie zostały na "Nieprzetłumaczalne";
   poprawione i zweryfikowane bezpośrednio w bazie ustawień WPML.
6. Częściowo zrobione: dodano `apply_filters('wpml_object_id', ...)` dla
   wszystkich miejsc czytających ID `projekt` z ACF (patrz sekcja F).
   Zapytania `get_posts()`/`WP_Query` bez `suppress_filters` (najbardziej
   wrażliwy: `project-nav`) — nadal do zweryfikowania, teraz że jest już
   jeden przetłumaczony projekt do testu.
7. ✅ Przetłumaczono jeden projekt portfolio end-to-end jako test:
   "Dom jednorodzinny, Krzyki Wrocław" → "Single-Family Home, Krzyki
   Wrocław" (`/en/projekt/single-family-home-krzyki-wroclaw/`). WPML's
   wbudowane tłumaczenie AI (PTC) przetłumaczyło tytuł/lead/opis/kategorię/
   status; przejrzane i poprawione jedno miejsce (dosłowne "second line of
   development" → bardziej naturalne "second row of development"). Do tego
   przetłumaczono w Tłumaczeniu ciągów znaków wszystkie stringi z kategorii
   A/B/C/D (nawigacja, stopka, 404, metryki, tabela nagród, banner cookies)
   — pełna lista w kodzie, ~30 stringów. Zweryfikowane end-to-end na
   produkcji: strona projektu, 404, banner cookies, stopka — wszystko po
   angielsku, strona polska bez regresji.
8. Jeśli działa — reszta treści (wciąż do zrobienia: pozostałe ~46
   projektów, zespół, nagrody, publikacje, strony O nas/Kontakt/
   Osiągnięcia/Home).
