@extends('layouts.store')

@section('content')
@php
    $locale = app()->getLocale();

    $copy = match($locale) {
        'az' => [
            'eyebrow' => 'Dəstək',
            'title' => 'Kömək Mərkəzi',
            'intro' => 'Sifariş, ödəniş, çatdırılma və geri ödənişlə bağlı əsas suallara cavablar.',
            'cta_title' => 'Hələ də kömək lazımdır?',
            'cta_text' => 'Dəstəyə yazarkən sifariş nömrəni əlavə et.',
            'cta' => 'Dəstəklə əlaqə',
            'faqs' => [
                ['Sifarişimi harada görə bilərəm?', 'Hesabına daxil olmusansa Hesabım bölməsindən, qonaq sifarişidirsə Sifarişi izlə səhifəsində sifariş nömrəsi və checkout zamanı istifadə olunan email ilə baxa bilərsən.'],
                ['Ödəniş uğurludur, amma sifariş emaldadır. Nə etməliyəm?', 'Bəzi sifarişlər ödəniş yoxlaması və ya təhlükəsizlik baxışı tələb edə bilər. Dəstəyə yazmazdan əvvəl sifariş statusunu yoxla.'],
                ['Rəqəmsal məhsullar necə çatdırılır?', 'Aktiv və təsdiqlənmiş sifarişlər rəqəmsal şəkildə fulfillment olunur. Tələb olunan məlumat və çatdırılma üsulu məhsuldan asılıdır.'],
                ['Geri ödəniş mümkündür?', 'Uğursuz və fulfillment olunmamış sifariş geri ödəniş və ya əvəzləmə üçün yoxlanıla bilər. Uğurla çatdırılmış rəqəmsal məhsullar geri qaytarılmaz ola bilər.'],
                ['Səhv Player ID daxil etmişəmsə nə olur?', 'Dərhal dəstəklə əlaqə saxla. Fulfillment tamamlandıqdan sonra rəqəmsal məhsul geri çevrilməyə bilər, buna görə məlumatları ödənişdən əvvəl yoxlamaq vacibdir.'],
                ['Sifariş niyə gecikə bilər?', 'Ödəniş yoxlaması, fraud prevention, manual təhlükəsizlik baxışı, təchizatçı əlçatanlığı və texniki yoxlamalar səbəb ola bilər.'],
            ],
        ],
        'ru' => [
            'eyebrow' => 'Поддержка',
            'title' => 'Центр помощи',
            'intro' => 'Основные ответы по заказам, оплате, доставке и возвратам.',
            'cta_title' => 'Нужна дополнительная помощь?',
            'cta_text' => 'При обращении в поддержку укажите номер заказа.',
            'cta' => 'Связаться с поддержкой',
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
            'cta_text' => 'Include your order number when contacting support.',
            'cta' => 'Contact support',
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

<section class="mx-auto max-w-5xl px-5 py-20 lg:px-8">
    <div class="text-center">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">{{ $copy['eyebrow'] }}</p>
        <h1 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">{{ $copy['title'] }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-slate-400">{{ $copy['intro'] }}</p>
    </div>

    <div class="mt-12 space-y-4">
        @foreach($copy['faqs'] as [$question, $answer])
            <details class="group pc-panel rounded-2xl">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-5 p-6 font-semibold text-white">
                    {{ $question }}
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-500/10 text-violet-300 transition group-open:rotate-45">+</span>
                </summary>
                <div class="px-6 pb-6 text-sm leading-7 text-slate-400">{{ $answer }}</div>
            </details>
        @endforeach
    </div>

    <div class="relative mt-10 overflow-hidden rounded-[2rem] border border-violet-400/20 bg-gradient-to-r from-violet-600/10 via-fuchsia-500/10 to-cyan-500/10 p-8 text-center">
        <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-violet-400/10 blur-3xl"></div>
        <div class="relative">
            <h2 class="text-xl font-black">{{ $copy['cta_title'] }}</h2>
            <p class="mt-2 text-sm text-slate-400">{{ $copy['cta_text'] }}</p>
            <a href="{{ route('contact') }}" class="pc-button mt-5 inline-flex rounded-xl px-6 py-3 text-sm font-bold text-white">{{ $copy['cta'] }}</a>
        </div>
    </div>
</section>
@endsection
