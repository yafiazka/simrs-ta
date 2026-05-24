{{-- 
    Tujuan      : Elemen background dekoratif halaman login (floating medical icons + ECG line)
    Caller      : AdminPanelProvider render hook 'panels::simple.page.start'
    Dependensi  : app.css (CSS animasi .simrs-bubble, .simrs-ecg-*)
    Side Effects: Tidak ada (aria-hidden, pointer-events: none)
--}} 
<div class="simrs-login-bg" aria-hidden="true">

    {{-- Floating Medical Bubble 1: Medical Cross (kiri atas, besar) --}}
    <div class="simrs-bubble" style="--sz:130px;--top:8%;--left:4%;--dur:8s;--del:0s;">
        <i class="fa-solid fa-circle-plus"></i>
    </div>

    {{-- Floating Medical Bubble 2: DNA helix (kanan atas, sedang) --}}
    <div class="simrs-bubble" style="--sz:100px;--top:6%;--left:78%;--dur:11s;--del:2s;">
        <i class="fa-solid fa-dna"></i>
    </div>

    {{-- Floating Medical Bubble 3: Stethoscope (kiri tengah, kecil) --}}
    <div class="simrs-bubble" style="--sz:82px;--top:42%;--left:7%;--dur:9s;--del:1.5s;">
        <i class="fa-solid fa-stethoscope"></i>
    </div>

    {{-- Floating Medical Bubble 4: Pill (kanan tengah, besar) --}}
    <div class="simrs-bubble" style="--sz:120px;--top:38%;--left:82%;--dur:10s;--del:3s;">
        <i class="fa-solid fa-pills"></i>
    </div>

    {{-- Floating Medical Bubble 5: Clipboard (kiri bawah, sedang) --}}
    <div class="simrs-bubble" style="--sz:95px;--top:70%;--left:12%;--dur:7s;--del:4s;">
        <i class="fa-solid fa-file-waveform"></i>
    </div>

    {{-- Floating Medical Bubble 6: Heart (kanan bawah, kecil) --}}
    <div class="simrs-bubble" style="--sz:78px;--top:72%;--left:75%;--dur:6s;--del:0.5s;">
        <i class="fa-solid fa-heart-pulse"></i>
    </div>

    {{-- Floating Medical Bubble 7: Syringe (tengah kanan bawah, medium) --}}
    <div class="simrs-bubble" style="--sz:88px;--top:20%;--left:50%;--dur:13s;--del:6s;">
        <i class="fa-solid fa-syringe"></i>
    </div>

    {{-- ECG / Denyut Jantung Line di bagian bawah --}}
    <div class="simrs-ecg-wrap">
        <svg class="simrs-ecg-svg" viewBox="0 0 1200 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <polyline
                class="simrs-ecg-line"
                points="
                    0,38 30,38 40,33 50,43 58,8  63,68 68,38 80,38 95,28 112,38
                    200,38 230,38 240,33 250,43 258,8  263,68 268,38 280,38 295,28 312,38
                    400,38 430,38 440,33 450,43 458,8  463,68 468,38 480,38 495,28 512,38
                    600,38 630,38 640,33 650,43 658,8  663,68 668,38 680,38 695,28 712,38
                    800,38 830,38 840,33 850,43 858,8  863,68 868,38 880,38 895,28 912,38
                    1000,38 1030,38 1040,33 1050,43 1058,8  1063,68 1068,38 1080,38 1095,28 1112,38
                    1200,38
                "
            />
        </svg>
    </div>

</div>
