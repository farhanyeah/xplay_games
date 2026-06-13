<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'XPLAY Games')</title>

    <link rel="icon" href="{{ asset('images/main/xplay.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    @stack('styles')

    <style>
        .xplay-chat-widget {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            font-family: Inter, system-ui, sans-serif;
        }

        .xplay-chat-toggle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            background: #6366f1;
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 14px 30px rgba(99, 102, 241, 0.25);
            display: grid;
            place-items: center;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .xplay-chat-toggle:hover {
            transform: translateY(-2px);
            background: #4f46e5;
        }

        .xplay-chat-panel {
            width: 340px;
            max-height: 520px;
            background: rgba(15, 23, 42, 0.98);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.35);
            overflow: hidden;
            display: none;
            flex-direction: column;
            backdrop-filter: blur(18px);
        }

        .xplay-chat-panel.active {
            display: flex;
        }

        .xplay-chat-header {
            padding: 18px 18px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: linear-gradient(180deg, rgba(31, 41, 55, 0.96), rgba(15, 23, 42, 0.92));
        }

        .xplay-chat-header strong {
            color: #f8fafc;
            display: block;
            font-size: 15px;
            letter-spacing: 0.02em;
        }

        .xplay-chat-header .subtitle {
            color: #cbd5e1;
            font-size: 12px;
            margin-top: 4px;
        }

        .xplay-chat-close {
            background: transparent;
            border: none;
            color: #cbd5e1;
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
        }

        .xplay-chat-body {
            padding: 14px 16px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            min-height: 210px;
            max-height: 320px;
        }

        .xplay-chat-message {
            padding: 12px 14px;
            border-radius: 18px;
            line-height: 1.6;
            font-size: 14px;
            word-break: break-word;
            white-space: pre-line;
        }

        .xplay-chat-message.user {
            align-self: flex-end;
            background: #4f46e5;
            color: #ffffff;
            border-bottom-right-radius: 6px;
        }

        .xplay-chat-message.assistant {
            align-self: flex-start;
            background: rgba(148, 163, 184, 0.15);
            color: #e2e8f0;
            border-bottom-left-radius: 6px;
        }

        .xplay-chat-empty {
            color: #94a3b8;
            padding: 18px 14px;
            text-align: center;
            font-size: 13px;
            line-height: 1.5;
            background: rgba(148, 163, 184, 0.08);
            border-radius: 16px;
        }

        .xplay-chat-form {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 14px 16px 16px;
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid rgba(148, 163, 184, 0.12);
        }

        .xplay-chat-input {
            flex: 1;
            min-width: 0;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(15, 23, 42, 0.92);
            color: #f8fafc;
            font-size: 14px;
            outline: none;
        }

        .xplay-chat-input::placeholder {
            color: rgba(148, 163, 184, 0.75);
        }

        .xplay-chat-submit {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: #22c55e;
            color: #0f172a;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: transform 0.2s ease;
        }

        .xplay-chat-submit:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .xplay-chat-widget {
                right: 14px;
                bottom: 14px;
            }

            .xplay-chat-panel {
                width: min(100vw, 340px);
            }
        }
    </style>
</head>

<body>
    <!-- ===== NAVBAR ===== -->
    @include('components.navbar')

    <!-- ===== CONTENT ===== -->
    @yield('content')

    <!-- Footer -->
    @include('components.footer')

    <!-- XPLAY Chat Widget
    <div class="xplay-chat-widget">
        <div class="xplay-chat-panel" id="xplayChatPanel" aria-hidden="true">
            <div class="xplay-chat-header">
                <div>
                    <strong>XPLAY Chat</strong>
                    <div class="subtitle">Tanya layanan XPLAY kapan saja</div>
                </div>
                <button type="button" class="xplay-chat-close" id="xplayChatClose" aria-label="Tutup chat">&times;</button>
            </div>
            <div class="xplay-chat-body" id="xplayChatBody">
                <div class="xplay-chat-empty">Halo! Tanyakan tentang sewa, unit, harga, atau layanan XPLAY.</div>
            </div>
            <form class="xplay-chat-form" id="xplayChatForm">
                <input type="text" id="xplayChatInput" class="xplay-chat-input" placeholder="Ketik pesan..." autocomplete="off" />
                <button type="submit" class="xplay-chat-submit" aria-label="Kirim pesan"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
        <button type="button" class="xplay-chat-toggle" id="xplayChatToggle" aria-label="Buka chat XPLAY">
            <i class="fas fa-comments"></i>
        </button>
    </div> -->

    <!-- Main JavaScript -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
    </script>

    <!-- Swiper.js -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('midtrans.client_key') }}">
    </script>


    <!-- Notifikasi ketika berhasil -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#6366f1',
        });
    </script>
    @endif

    <!-- Sweet Alert Logout Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutButtons = document.querySelectorAll('.btn-logout-confirm');

            logoutButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'question',
                        title: 'Konfirmasi Logout',
                        text: 'Apakah Anda yakin ingin logout?',
                        showCancelButton: true,
                        confirmButtonText: 'Logout',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#94a3b8'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                });
            });
        });
    </script>

    <!-- Notifikasi ketika berhasil logout -->
    <script>
        @if(session('logout'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('logout') }}",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
        });
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatPanel = document.getElementById('xplayChatPanel');
            const chatToggle = document.getElementById('xplayChatToggle');
            const chatClose = document.getElementById('xplayChatClose');
            const chatForm = document.getElementById('xplayChatForm');
            const chatInput = document.getElementById('xplayChatInput');
            const chatBody = document.getElementById('xplayChatBody');
            const storageKey = 'xplayChatHistory';

            let chatHistory = JSON.parse(localStorage.getItem(storageKey) || '[]');

            const renderMessages = () => {
                if (chatHistory.length === 0) {
                    chatBody.innerHTML = '<div class="xplay-chat-empty">Halo! Tanyakan tentang sewa, unit, harga, atau layanan XPLAY.</div>';
                    return;
                }

                chatBody.innerHTML = chatHistory.map(message => {
                    const roleClass = message.role === 'user' ? 'user' : 'assistant';
                    return `<div class="xplay-chat-message ${roleClass}">${message.content}</div>`;
                }).join('');

                chatBody.scrollTop = chatBody.scrollHeight;
            };

            const saveHistory = () => {
                localStorage.setItem(storageKey, JSON.stringify(chatHistory));
            };

            const addMessage = (role, content) => {
                chatHistory.push({ role, content });
                saveHistory();
                renderMessages();
            };

            chatToggle.addEventListener('click', () => {
                chatPanel.classList.toggle('active');
                if (chatPanel.classList.contains('active')) {
                    chatInput.focus();
                }
            });

            chatClose.addEventListener('click', () => {
                chatPanel.classList.remove('active');
            });

            chatForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const messageText = chatInput.value.trim();
                if (!messageText) {
                    return;
                }

                addMessage('user', messageText);
                chatInput.value = '';

                try {
                    const response = await fetch('/xplay/chat/history', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            message: messageText,
                            history: chatHistory
                        })
                    });

                    const data = await response.json();
                    console.log('Chat response', data);

                    if (data.success) {
                        addMessage('assistant', data.response);
                    } else {
                        const errorMessage = data.message || 'Maaf, terjadi kesalahan saat memproses permintaan Anda.';
                        addMessage('assistant', errorMessage);
                    }
                } catch (error) {
                    console.error(error);
                    addMessage('assistant', 'Gagal terhubung ke server. Silakan coba lagi.');
                }
            });

            renderMessages();
        });
    </script>

    @stack('scripts')

</body>

</html>