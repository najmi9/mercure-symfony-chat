import '../styles/app.css';

const input = document.querySelector('#registration_form_file');
if (input) {
    input.onchange = () => readURL(input);
    function readURL(input) {

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                document.querySelector('.picture-src').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}