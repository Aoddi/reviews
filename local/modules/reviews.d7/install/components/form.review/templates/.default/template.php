<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<h2 class="">Поделитесь своим отзывом</h2>

<form action="" name="reviewsForm" style="display: flex; flex-direction: column">
    <label for="" style="display: flex; flex-direction: column">
        Оценка:
        <input type="number" name="estimation" class="" id="form-estimation" min="0" max="5" required>
    </label>
    <br>
    <label for="" style="display: flex; flex-direction: column">
        Имя:
        <input type="text" name="name" id="form-name" required>
    </label>
    <br>
    <label for="" style="display: flex; flex-direction: column">
        Текст:
        <input type="text" name="text" id="form-text" required>
    </label>
    <br>
    <input type="hidden" name="product" id="form-product" value="<?=$arParams['ID']?>">
    <button type="submit">Отправить отзыв</button>
</form>

<script>
    let formReview = document.forms.reviewsForm;

    formReview.addEventListener('submit', e => {
        e.preventDefault();

        let name = document.querySelector('#form-name').value;
        let estimation = document.querySelector('#form-estimation').value;
        let text = document.querySelector('#form-text').value;
        let product = document.querySelector('#form-product').value;

        let upload = {
            "name": name,
            "estimation": estimation,
            "text": text,
            "product": product,
        }

        let data = new FormData();
        data.append("ajax", JSON.stringify(upload));

        fetch('/local/components/reviews.d7/form.review/ajax/ajax.php',
            {
                method: "POST",
                body: data
            })
            .then(response => {
                console.log(response);
                if (response.status !== 200) {
                    return Promise.reject();
                }
                alert('Отзыв отправлен успешно')
                return response.json();

            })
            .then(function (data) {
                alert('Отзыв отправлен успешно')
                console.log(JSON.stringify(data))
            })
            .catch(error => {console.log('ошибка')});

    })
</script>