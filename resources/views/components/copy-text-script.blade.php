<script>
    /**
     *  Функция для копирования текста.
     *
     *  Добавляется через атрибут onclick="copyToClipboard('some_text_for_copy')".
     *
     * @param commandText текст, который будет скопирован в буфер обмена
     */
    function copyToClipboard(commandText) {
        // Создаем временный элемент textarea:
        let textarea = document.createElement('textarea');

        textarea.value = commandText;

        document.body.appendChild(textarea);

        // Выделяем текст в textarea:
        textarea.select();
        textarea.setSelectionRange(0, 99999);

        // Копируем выделенный текст в буфер обмена:
        document.execCommand('copy');

        // Удаляем временный элемент textarea
        document.body.removeChild(textarea);
    }
</script>
