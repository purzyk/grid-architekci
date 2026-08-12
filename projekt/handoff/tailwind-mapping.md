# GRID Architekci — Tailwind mapping

Every pattern in the mocks, written as Tailwind classes. Pair with `tailwind.config.js`
in this folder — the token names below (`accent`, `ink/50`, `divider`, `ease-reveal`)
come from it.

Load Archivo (weights 400 and 800) — the mocks use no other typeface.

---

## Page shell

The mocks are drawn on a fixed 1360px canvas. In production the page is fluid
and caps at that width.

```html
<body class="bg-desk">
  <div class="mx-auto w-full max-w-page bg-paper px-[18px] pt-5 pb-11
              sm:px-7 sm:pt-6 sm:pb-14 md:px-10 md:pt-[30px] md:pb-[70px]">
```

## Header

Logo left, nav right, PL/EN last. Wraps to two rows below `sm`.

```html
<div class="flex flex-wrap items-center justify-between gap-3.5
            border-b-2 border-divider pb-3.5 md:flex-nowrap md:items-end">
  <a href="/"><img src="logo.svg" class="block h-8 w-auto" alt="GRID Architekci"></a>
  <nav class="flex flex-wrap items-center gap-x-[18px] gap-y-3.5 md:gap-7">
    <a class="border-b border-transparent pb-0.5 text-meta uppercase tracking-nav
              text-ink/50 hover:text-ink
              aria-[current=page]:border-accent aria-[current=page]:text-ink">Projekty</a>
    <!-- … -->
  </nav>
</div>
```

Active item: `aria-current="page"` drives the accent underline — do not hard-code
an `.active` class.

## Hero

Collapses from two columns to one at `md`.

```html
<div class="grid grid-cols-1 items-end gap-[26px] pb-[26px] pt-10
            md:grid-cols-[1fr_420px] md:gap-16 md:pb-[30px] md:pt-14">
  <h1 class="m-0 text-[36px] font-extrabold uppercase leading-[0.92] tracking-tightest
             sm:text-[52px] md:max-w-measure md:text-display">…</h1>
  <div>
    <p class="mb-[18px] text-body text-ink/70">…</p>
    <div class="flex gap-[22px] border-t-2 border-divider pt-3.5 md:gap-10">
      <!-- stat -->
      <div>
        <div class="text-[30px] font-extrabold leading-none tracking-[-0.03em] text-accent">23</div>
        <div class="mt-[5px] text-label uppercase tracking-nav text-ink/50">lata pracowni</div>
      </div>
    </div>
  </div>
</div>
```

## Filter bar

```html
<div class="mb-[26px] flex flex-col items-start gap-3 border-t-2 border-divider pt-3.5
            sm:flex-row sm:items-baseline sm:justify-between">
  <div class="flex gap-[22px]">
    <button class="border-b-2 border-transparent pb-[3px] text-meta font-extrabold
                   uppercase tracking-nav text-ink/50
                   aria-pressed:border-accent aria-pressed:text-accent">Wszystkie</button>
  </div>
  <span class="text-meta text-ink/45">12 / 18</span>
</div>
```

## Project grid

1 → 2 → 3 columns. Every seventh tile spans two columns; `grid-flow-dense`
back-fills the gap it leaves.

```html
<div class="grid grid-flow-dense grid-cols-1 items-start gap-[30px]
            sm:grid-cols-2 sm:gap-x-5 sm:gap-y-8
            md:grid-cols-3 md:gap-x-8 md:gap-y-10">

  <!-- tile; wide tile adds: sm:col-span-2 -->
  <a href="/projekt/…" class="group block text-inherit">
    <div class="relative aspect-tile w-full overflow-hidden bg-surface">
      <img class="absolute inset-0 h-full w-full object-cover saturate-60
                  transition-[transform,filter] duration-700 ease-reveal
                  group-hover:scale-[1.03] group-hover:saturate-100" src="…" alt="">
      <div class="absolute inset-0 bg-accent opacity-0 mix-blend-multiply
                  transition-opacity duration-450 group-hover:opacity-28"></div>
    </div>
    <div class="mt-3 flex items-baseline justify-between gap-4 border-t-2 border-divider pt-2.5">
      <span class="text-tile font-extrabold uppercase transition-colors
                   group-hover:text-accent">Dom Kultury, Zapolice</span>
      <span class="whitespace-nowrap text-label uppercase tracking-nav text-ink/50">2022</span>
    </div>
    <div class="mt-1 flex gap-[7px] text-meta uppercase tracking-[0.1em] text-ink/45">
      <span>Publiczne</span><span>Zrealizowane</span>
    </div>
  </a>

  <!-- "show more" occupies the last cell; its rule aligns with the tile rules above -->
  <button class="flex w-full items-end pt-1.5 sm:aspect-tile sm:pt-0">
    <span class="border-b-2 border-accent pb-1 text-meta font-extrabold uppercase
                 tracking-[0.16em] text-accent sm:translate-y-3.5">Pokaż więcej projektów</span>
  </button>
</div>
```

The hover mask is the one effect the client asked for by name — accent at 28%
in `multiply`, over a photograph that goes from 60% to full saturation.

## Project page — spec block

Five columns of label/value, even rhythm above, between and below.

```html
<div class="grid grid-cols-2 gap-[26px] border-t-2 border-divider px-[18px] py-[26px]
            sm:grid-cols-3 md:grid-cols-5 md:px-10">
  <div>
    <div class="mb-1.5 h-3 text-label uppercase tracking-kicker text-ink/50">Klient</div>
    <div class="text-body-sm leading-[1.4]">Gmina Zapolice</div>
  </div>
</div>
```

The `h-3` on the label is deliberate: it keeps both rows on one baseline when a
label wraps to two words.

## Awards table

Rows are links. Below `sm` the four columns become a stacked block.

```html
<a href="/projekt/…"
   class="grid grid-cols-[58px_1fr_1fr_156px] items-baseline gap-5
          border-b border-hairline py-2 text-inherit hover:bg-accent/[0.07]">
  <span class="text-[15px] font-extrabold">2003</span>
  <span class="text-sm">Europan 7, Kristianstad</span>
  <span class="text-sm text-ink/60">Szwecja</span>
  <span class="text-label font-extrabold uppercase tracking-[0.1em] text-accent">I nagroda</span>
</a>
```

## Publication covers

Six per row, image contained (never cropped — the covers have wildly different
proportions).

```html
<a href="…" class="group block text-inherit">
  <div class="aspect-cover w-full overflow-hidden bg-surface">
    <img class="h-full w-full object-contain saturate-[.55]
                transition-[transform,filter] duration-600 ease-reveal
                group-hover:scale-[1.06] group-hover:saturate-100" src="…" alt="">
  </div>
  <div class="mt-2.5 border-t-2 border-divider pt-2 text-[13px] font-extrabold leading-[1.25]">…</div>
  <div class="mt-1 flex justify-between gap-2 text-label uppercase tracking-[0.08em] text-ink/50">
    <span>Prasa branżowa</span><span>2021</span>
  </div>
</a>
```

## Highlight plates

Three plates: accent fill, ink fill, surface fill. Arrow slides in on hover.

```html
<a href="…" class="group relative block overflow-hidden bg-accent p-7 pb-[34px] text-paper">
  <div class="text-[46px] font-extrabold leading-none tracking-tightest">2003</div>
  <div class="my-3 text-label uppercase tracking-[0.18em] opacity-75">I nagroda</div>
  <div class="text-[22px] font-extrabold uppercase leading-[1.1] tracking-[-0.02em]">Europan 7</div>
  <p class="mt-2 text-body-sm opacity-80">…</p>
  <span class="absolute bottom-[26px] right-6 -translate-x-2 text-[22px] font-extrabold opacity-0
               transition-[opacity,transform] duration-450 ease-reveal
               group-hover:translate-x-0 group-hover:opacity-90">→</span>
  <span class="pointer-events-none absolute inset-0 bg-current opacity-0
               transition-opacity duration-350 group-hover:opacity-[0.07]"></span>
</a>
```

---

## Rules that hold everywhere

- **No rounded corners.** `borderRadius` is `none` only — if a radius appears, it is a bug.
- **Rules, not boxes.** `border-t-2 border-divider` separates sections; 1px `border-hairline` separates rows. No cards, no shadows.
- **Flush left.** Nothing is centred — not headings, not button labels.
- **Photographs desaturate at rest** (`saturate-60`) and reach full colour on hover.
  Publication covers are `object-contain`; every other image is `object-cover`.
- **One easing** (`ease-reveal`) and three durations (450/600/700ms) across the whole site.
- **Accent at body size must be `accent-700`** — `accent` (#ff6633) on paper is
  under 4.5:1 and fails contrast for small text. Use it for large type, rules and interface chrome.

## Touch

Every effect above is `group-hover`, which does not exist on touch. On mobile the
tiles need a visible pressed state instead — `active:opacity-90` on the link, or
show the accent mask on `:active`. Decide this before build; the mocks are desktop-only.
