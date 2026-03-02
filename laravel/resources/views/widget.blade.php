@extends('layouts.iframe')

@section('content')
    <div id="toast" class="toast"></div>

    <div class="widget-wrap">
        <div class="widget-card">
            <h1 class="widget-title">Обратная связь</h1>
            <p class="widget-subtitle">Оставьте заявку, и мы свяжемся с вами</p>

            <form id="ticket-form" class="form" novalidate>
                <div class="field">
                    <label class="label" for="name">Имя</label>
                    <input class="input" id="name" name="name" type="text" autocomplete="name">
                    <div class="error" data-error-for="name"></div>
                </div>

                <div class="field">
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" autocomplete="email">
                    <div class="error" data-error-for="email"></div>
                </div>

                <div class="field">
                    <label class="label" for="phone_e164">Телефон (E.164)</label>
                    <input class="input" id="phone_e164" name="phone_e164" type="text" placeholder="+15551234567">
                    <div class="error" data-error-for="phone_e164"></div>
                </div>

                <div class="field">
                    <label class="label" for="subject">Тема</label>
                    <input class="input" id="subject" name="subject" type="text">
                    <div class="error" data-error-for="subject"></div>
                </div>

                <div class="field">
                    <label class="label" for="message">Сообщение</label>
                    <textarea class="textarea" id="message" name="message" rows="4"></textarea>
                    <div class="hint">Опишите вопрос как можно подробнее</div>
                    <div class="error" data-error-for="message"></div>
                </div>

                <div class="field">
                    <label class="label" for="attachments">Файлы</label>
                    <input class="file" id="attachments" name="attachments[]" type="file" multiple>
                    <div class="hint">Можно прикрепить несколько файлов (jpg/png/pdf/doc/docx)</div>
                    <div class="error" data-error-for="attachments"></div>
                </div>

                <div class="actions">
                    <button class="btn" id="submit-btn" type="submit">Отправить</button>
                    <span class="loading" id="loading">Отправка…</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('ticket-form');
        const submitBtn = document.getElementById('submit-btn');
        const loading = document.getElementById('loading');
        const toast = document.getElementById('toast');

        function setLoading(state) {
            submitBtn.disabled = state;
            loading.style.display = state ? 'inline' : 'none';
        }

        function clearFieldErrors() {
            document.querySelectorAll('[data-error-for]').forEach((el) => (el.textContent = ''));
            form.querySelectorAll('input, textarea').forEach((el) => el.classList.remove('is-invalid'));
        }

        function markInvalid(field) {
            const selector = `[name="${CSS.escape(field)}"]`;
            const el = form.querySelector(selector);
            if (el) el.classList.add('is-invalid');
        }

        function setFieldErrors(errors) {
            Object.keys(errors).forEach((field) => {
                const normalized = field.startsWith('attachments.') ? 'attachments' : field;

                const target = document.querySelector(`[data-error-for="${normalized}"]`);
                if (target && !target.textContent) target.textContent = errors[field][0];

                if (normalized === 'attachments') {
                    markInvalid('attachments[]');
                } else {
                    markInvalid(field);
                }
            });
        }

        let toastTimer = null;

        function showToast(type, message) {
            if (toastTimer) clearTimeout(toastTimer);

            toast.className = 'toast ' + (type === 'success' ? 'success' : 'error');
            toast.textContent = message;
            toast.classList.add('show');

            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 4500);
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFieldErrors();
            setLoading(true);

            const formData = new FormData(form);

            try {
                const response = await fetch('/api/tickets', {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: formData,
                });

                const data = await response.json().catch(() => null);

                if (!response.ok) {
                    if (response.status === 422 && data && data.errors) {
                        setFieldErrors(data.errors);

                        const msg = data?.message ?? 'Проверьте корректность заполнения полей формы';
                        showToast('error', msg);
                    } else {
                        showToast('error', 'Произошла ошибка при отправке. Попробуйте позже.');
                    }
                    return;
                }

                form.reset();
                showToast('success', 'Заявка отправлена! Мы свяжемся с вами.');
            } catch (err) {
                showToast('error', 'Ошибка сети. Проверьте соединение и попробуйте снова.');
            } finally {
                setLoading(false);
            }
        });
    </script>
@endsection
