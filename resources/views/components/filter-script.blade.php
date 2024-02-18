<script>
    document.addEventListener('DOMContentLoaded', handleFilterFormSubmit);

    /**
     *  Метод проверяет наличие заполенных полей фильтров и добавляет их к URL для редиректа
     */
    function handleFilterFormSubmit() {
        const filterForm = document.getElementById('{{ $idFilterForm }}');

        if (!filterForm) return;

        filterForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const filteredData = {};

            // Пропускаем пустые поля формы Фильтра:
            formData.forEach(function(value, key) {
                if (value !== '' && value !== '0') {
                    filteredData[key] = value;
                }
            });

            const queryParameters = new URLSearchParams(filteredData).toString();
            const action = this.getAttribute('action');

            window.location.href = `${action}?${queryParameters}`;
        });
    }
</script>
