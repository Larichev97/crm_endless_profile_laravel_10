<script>
    /**
     *  Функция для копирования текста.
     *
     *  Добавляется через атрибут onclick="copyToClipboard('some_id_name')".
     *
     * @param commandTextId ID тега, из которого будет скопирован текст
     */
    function copyToClipboard(commandTextId) {
        let commandText = document.getElementById(commandTextId);

        // Создаем временный элемент textarea:
        let textarea = document.createElement('textarea');

        textarea.value = commandText.innerText;

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
