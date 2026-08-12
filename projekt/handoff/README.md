# GRID Architekci — pakiet wdrożeniowy

Redesign strony pracowni GRID Architekci (Wrocław). Pięć stron, dwujęzyczne (PL/EN),
responsywne, zaakceptowane przez klienta.

Ten folder zawiera specyfikację dla dewelopera. Same makiety leżą w katalogu nadrzędnym.

---

## Pliki

| Plik | Strona | Zawartość |
|---|---|---|
| `index.dc.html` | Projekty (główna) | hero + statystyki, filtry, siatka projektów, „Aktualne projekty", stopka |
| `grid-projekt.dc.html` | Pojedynczy projekt | tytuł + lead, zdjęcia pełnej szerokości, rysunek zagospodarowania, metryka, prev/next |
| `grid-o-nas.dc.html` | O nas | manifest, pasek zdjęciowy, zakres pracy, oś czasu 23 lat, zespół + współpracownicy |
| `grid-osiagniecia.dc.html` | Osiągnięcia | trzy wyróżnione nagrody, tabela 17 nagród, siatka publikacji |
| `grid-kontakt.dc.html` | Kontakt | dane + dane rejestrowe, formularz, mapa, proces w 5 krokach |

W tym folderze:
- `tailwind.config.js` — tokeny do wklejenia w projekt
- `tailwind-mapping.md` — każdy wzorzec UI zapisany klasami Tailwinda

Makiety ładują Tailwind z CDN i mają tę samą konfigurację wpisaną inline —
w produkcji użyj `tailwind.config.js` z tego folderu.

## Stack makiet

Czysty HTML + Tailwind (CDN). Bez frameworka, bez budowania. Logika (dane projektów,
przełącznik języka, filtry, „pokaż więcej") siedzi w jednej klasie JS na końcu każdego
pliku — traktuj ją jako **specyfikację danych i zachowań**, nie jako kod produkcyjny.

Typografia: **Archivo** (400 i 800). Innego kroju nie ma nigdzie.

## Breakpointy

| | zakres | siatka projektów | menu |
|---|---|---|---|
| telefon | < 620px | 1 kolumna | hamburger |
| tablet | 620–899px | 2 kolumny | hamburger |
| desktop | 900–1439px | 3 kolumny | poziome |
| szeroki | ≥ 1440px | 3 kolumny, kanwa 1360px wyśrodkowana | poziome |

Nagłówki skalują się płynnie przez `clamp()` — nie skaczą na breakpointach i nie
przepełniają viewportu w żadnej szerokości pośredniej.

## Struktura danych

Do CMS-a trzeba wynieść trzy kolekcje. Pola odczytane wprost z makiet:

**Projekt** — `id`, `tytuł PL/EN`, `rok`, `kategoria` (mieszkalne | publiczne | przemysłowe),
`status` (zrealizowane | w realizacji | konkurs | koncepcja), `nagroda` (opcjonalnie),
`zdjęcie główne`, `opis krótki PL/EN`, `lead PL/EN`, `dwa akapity opisu PL/EN`,
`galeria` (zdjęcia + podpisy), `rysunek zagospodarowania`, oraz metryka:
klient, rok, status, zakres, zespół, konstrukcja, instalacje, wykonawca, zdjęcia, nagrody.

**Nagroda** — `rok`, `nazwa konkursu PL/EN`, `projekt PL/EN`, `wynik PL/EN`,
`wyróżniona` (bool — sterują kolorem i trafiają na trzy płyty u góry), `link do projektu`.

**Publikacja** — `tytuł`, `typ PL/EN`, `rok`, `okładka`, `link` (artykuł online lub skan PDF).

Teksty stron statycznych (O nas, proces, FAQ) też są dwujęzyczne — w CMS jako pola
tekstowe, nie hard-code.

## Zachowania

- **Przełącznik PL/EN** — w makietach trzyma stan w komponencie. W produkcji: osobne
  adresy (`/pl/…`, `/en/…`) ze względu na SEO i możliwość linkowania.
- **Filtry kategorii** — filtrowanie po stronie klienta, bez przeładowania.
- **„Pokaż więcej projektów"** — pierwsze 12, potem reszta. Zajmuje ostatnią komórkę siatki.
- **Maska hover** — akcent #ff6633 na 28% w trybie `multiply` + zdjęcie z saturacji 60% do
  pełnej + `scale(1.03)`. To jedyny efekt, o który klient poprosił z nazwy.
- **Dotyk** — hover nie istnieje. Maska jest podpięta również pod `group-active`; przed
  wdrożeniem zdecydujcie, czy to wystarczy.

## Do dostarczenia przez klienta

Bez tego nie ma sensu zaczynać:

- zdjęcia w wysokiej rozdzielczości (obecne to 500px zaciągane ze starej strony)
- logo w SVG (teraz PNG 1018px — na ekranach retina miękkie)
- linki lub skany do 8 publikacji
- grafika mapy dojazdu
- zdjęcie do sekcji Kontakt (obecne oznaczone „do wymiany")
- metryki projektów: konstrukcja, instalacje, wykonawca, autor zdjęć
- **zatwierdzone odpowiedzi FAQ** — to zobowiązania handlowe, wersje w plikach są robocze
- weryfikacja angielskich tłumaczeń przez native speakera

## Decyzje techniczne przed startem

- **CMS czy statyczna strona** — przy ~50 projektach i regularnych aktualizacjach: CMS.
- **Formularz** — gdzie trafiają zapytania, jakie potwierdzenie dostaje nadawca.
- **Polityka prywatności i cookies** — w makietach jest tylko klauzula RODO pod formularzem.
- **Przekierowania ze starych adresów** — stara strona ma pozycje w Google od 2017.
  Bez mapy 301 przepadną.

## Czego w makietach nie ma

- strona 404
- stany błędów i walidacji formularza
- warianty strony projektu dla różnych typów (dom / hala / konkurs mają inne metryki)
- sekcja FAQ na stronie Kontakt — dane są w pliku, układu nie ma (decyzja klienta w toku)
- strona aktualności — zastąpiona blokiem „Aktualne projekty" na stronie głównej
