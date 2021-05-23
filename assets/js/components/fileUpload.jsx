import React from 'react';

const FileUpload = ({loading, postData}) => {

    const handleChange = (e) => {
        readURL(e.currentTarget);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            
            input.files.forEach(file => {
                const reader = new FileReader();
                reader.onload = async function (e) {
                    await postData(e.target.result);
                }
                reader.readAsDataURL(file);
            })
        }
    }

    return (
        <span className="img-wrapper btn border">
            <input disabled={loading} multiple={true} accept="image/png, image/jpeg, jpeg" className="btn add-image"
            onChange={handleChange} type="file"/>
            <i className="fas fa-image text-success image-icon"></i>
        </span>
    );
}

export default FileUpload;
