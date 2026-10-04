@extends('layouts.store')

@section('content')
@php
    $locale = app()->getLocale();

    $copy = match($locale) {
        'az' => [
            'eyebrow' => 'Dəstək',
            'title' => 'Kömək Mərkəzi',
            'intro' => 'Sifariş, ödəniş, çatdırılma və geri ödənişlə bağlı əsas suallara aydın cavablar.',
            'cta_title' => 'Hələ də kömək lazımdır?',
            'cta_text' => 'Dəstəyə yazarkən sifariş nömrəni əlavə et ki, müraciət daha tez yoxlanılsın.',
            'cta' => 'Dəstəklə əlaqə',
            'track' => 'Sifarişi izlə',
            'legal' => 'Qaydalar və siyasətlər',
            'faqs' => [
                ['Sifarişimi harada görə bilərəm?', 'Hesabına daxil olmusansa Hesabım bölməsindən, qonaq sifarişidirsə Sifarişi izlə səhifəsində sifariş nömrəsi və checkout zamanı istifadə olunan email ilə baxa bilərsən.'],
                ['Ödəniş uğurludur, amma sifariş emaldadır. Nə etməliyəm?', 'Bəzi sifarişlər ödəniş yoxlaması və ya təhlükəsizlik baxışı tələb edə bilər. Dəstəyə yazmazdan əvvəl sifariş statusunu yoxla.'],
                ['Rəqəmsal məhsullar necə çatdırılır?', 'Aktiv və təsdiqlənmiş sifarişlər rəqəmsal şəkildə çatdırılır. Tələb olunan məlumat və çatdırılma üsulu məhsuldan asılıdır.'],
                ['Geri ödəniş mümkündür?', 'Uğursuz və çatdırılmamış sifariş geri ödəniş və ya əvəzləmə üçün yoxlanıla bilər. Uğurla çatdırılmış rəqəmsal məhsullar geri qaytarılmaz ola bilər.'],
                ['Səhv Player ID daxil etmişəmsə nə olur?', 'Dərhal dəstəklə əlaqə saxla. Çatdırılma tamamlandıqdan sonra rəqəmsal məhsul geri çevrilməyə bilər, buna görə məlumatları ödənişdən əvvəl yoxlamaq vacibdir.'],
                ['Sifariş niyə gecikə bilər?', 'Ödəniş yoxlaması, fırıldaqçılığın qarşısının alınması, manual təhlükəsizlik baxışı, təchizatçı əlçatanlığı və texniki yoxlamalar səbəb ola bilər.'],
            ],
        ],
        'ru' => [
            'eyebrow' => 'Поддержка',
            'title' => 'Центр помощи',
            'intro' => 'Понятные ответы по заказам, оплате, доставке и возвратам.',
            'cta_title' => 'Нужна дополнительная помощь?',
            'cta_text' => 'При обращении в поддержку укажите номер заказа, чтобы мы быстрее нашли его.',
            'cta' => 'Связаться с поддержкой',
            'track' => 'Отследить заказ',
            'legal' => 'Правила и политики',
            'faqs' => [
                ['Где посмотреть мой заказ?', 'Войдите в аккаунт и откройте раздел «Мой аккаунт», либо используйте страницу отслеживания заказа с номером заказа и email, указанным при оформлении.'],
                ['Оплата прошла, но заказ всё ещё обрабатывается. Что делать?', 'Некоторые заказы требуют проверки оплаты или дополнительной проверки безопасности. Сначала проверьте страницу статуса заказа.'],
                ['Как доставляются цифровые товары?', 'Активные и подтверждённые заказы исполняются цифровым способом. Необходимые данные и способ доставки зависят от товара.'],
                ['Можно ли получить возврат?', 'Неисполненный неудачный заказ может быть рассмотрен для возврата или замены. Успешно доставленные цифровые товары могут быть необратимыми.'],
                ['Что если я ввёл неверный Player ID?', 'Свяжитесь с поддержкой как можно скорее. После завершения цифрового исполнения операция может стать необратимой.'],
                ['Почему заказ может задержаться?', 'Причиной могут быть проверка оплаты, предотвращение мошенничества, ручная проверка безопасности, доступность поставщика или технические проверки.'],
            ],
        ],
        default => [
            'eyebrow' => 'Support',
            'title' => 'Help Center',
            'intro' => 'Clear answers about orders, payments, delivery and refunds.',
            'cta_title' => 'Still need help?',
            'cta_text' => 'Include your order number when contacting support so your case can be found faster.',
            'cta' => 'Contact support',
            'track' => 'Track an order',
            'legal' => 'Policies and terms',
            'faqs' => [
                ['Where can I see my order?', 'Use My Account when signed in, or Track Order with the order number and email used during checkout.'],
                ['My payment was successful but the order is still processing. What should I do?', 'Some orders may require payment verification or a security review. Check the order status page before contacting support.'],
                ['How are digital products delivered?', 'Activated and approved orders are fulfilled digitally. Required information and delivery method depend on the product.'],
                ['Can I get a refund?', 'A failed order that has not been fulfilled may be reviewed for refund or replacement. Successfully delivered digital products may be irreversible.'],
                ['What if I entered the wrong Player ID?', 'Contact support immediately. Digital fulfillment may become irreversible once completed, so verify details before payment.'],
                ['Why can an order be delayed?', 'Payment verification, fraud prevention, manual security review, supplier availability or technical checks can cause a delay.'],
            ],
        ],
    };
@endphp

<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-80 bg-[radial-gradient(circle_at_50%_0%,rgba(124,58,237,.18),transparent_42%),radial-gradient(circle_at_15%_20%,rgba(34,211,238,.08),transparent_30%)]"></div>

    <div class="mx-auto max-w-6xl px-5 py-16 lg:px-8 lg:py-20">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-violet-400/20 bg-violet-500/10 text-xl text-violet-200">?</div>
            <p class="mt-6 text-xs font-bold uppercase tracking-[0.24em] text-violet-400">{{ $copy['eyebrow'] }}</p>
            <h1 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">{{ $copy['title'] }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-slate-400 sm:text-base">{{ $copy['intro'] }}</p>

            <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('orders.track') }}" class="pc-button inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white">{{ $copy['track'] }}</a>
                <a href="{{ route('legal.terms') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-violet-400/30 hover:text-white">{{ $copy['legal'] }}</a>
            </div>
        </div>

        <div class="mx-auto mt-12 grid max-w-5xl gap-4">
            @foreach($copy['faqs'] as $index => [$question, $answer])
                <details class="group overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0D1020]/90 transition open:border-violet-400/20 open:bg-[#11142A]">
                    <summary class="flex cursor-pointer list-none items-center gap-5 p-5 sm:p-6">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/[0.08] bg-white/[0.035] text-xs font-black text-violet-300">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex-1 text-left font-semibold text-white">{{ $question }}</span>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-500/10 text-violet-300 transition group-open:rotate-45">+</span>
                    </summary>
                    <div class="border-t border-white/[0.06] px-5 py-5 text-sm leading-7 text-slate-400 sm:px-[4.75rem] sm:py-6">{{ $answer }}</div>
                </details>
            @endforeach
        </div>

        <div class="relative mx-auto mt-10 max-w-5xl overflow-hidden rounded-[2rem] border border-violet-400/20 bg-gradient-to-r from-violet-600/12 via-fuchsia-500/8 to-cyan-500/10 p-8 sm:p-10">
            <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-violet-400/10 blur-3xl"></div>
            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-black">{{ $copy['cta_title'] }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">{{ $copy['cta_text'] }}</p>
                </div>
                <a href="{{ route('contact') }}" class="pc-button inline-flex shrink-0 items-center justify-center rounded-xl px-6 py-3 text-sm font-bold text-white">{{ $copy['cta'] }} →</a>
            </div>
        </div>
    </div>
</section>
@endsection
