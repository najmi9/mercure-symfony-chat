import React from 'react';
import { toast } from 'react-toastify';

const FileUpload = ({loading, postData}) => {

    const handleChange = (e) => {
        readURL(e.currentTarget);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            input.files.forEach(file => {
                if (file.size > 2000000) { // 2M
                    toast.error('File Size Must Be Less Than 2M.')
                    return;
                }
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
                <svg className="image-icon" width="25" height="25" viewBox="0 0 30 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 5.2C1 4.08609 1.59 3.0178 2.6402 2.23015C3.69041 1.4425 5.11479 1 6.6 1H23.4C24.8852 1 26.3096 1.4425 27.3598 2.23015C28.41 3.0178 29 4.08609 29 5.2V17.8C29 18.9139 28.41 19.9822 27.3598 20.7698C26.3096 21.5575 24.8852 22 23.4 22H6.6C5.11479 22 3.69041 21.5575 2.6402 20.7698C1.59 19.9822 1 18.9139 1 17.8V5.2Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                <path d="M10.1 10.45C12.033 10.45 13.6 9.27476 13.6 7.82501C13.6 6.37526 12.033 5.20001 10.1 5.20001C8.16701 5.20001 6.60001 6.37526 6.60001 7.82501C6.60001 9.27476 8.16701 10.45 10.1 10.45Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                <path d="M18.5364 12.1521L6.60001 22H23.778C25.163 22 26.4912 21.5874 27.4705 20.8529C28.4498 20.1184 29 19.1222 29 18.0835C29 17.5942 28.755 17.1228 28.314 16.7605L22.672 12.1458C22.4091 11.9306 22.0892 11.759 21.7328 11.6417C21.3765 11.5245 20.9915 11.4643 20.6024 11.4648C20.2132 11.4654 19.8285 11.5269 19.4728 11.6452C19.1171 11.7635 18.7982 11.9361 18.5364 12.1521V12.1521Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                </svg> 
        </span>
    );
}

export default FileUpload;
