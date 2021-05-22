import React from 'react';
import { createPortal } from 'react-dom';

const BigImage = ({src, id}) => {

    return createPortal(
        <div className="modal fade" id={"showImageModal-" + id} tabIndex="-1" aria-labelledby="showImageModalLabel" aria-hidden="true">
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                        <img src={src} alt="Big Image" width="100%" height="100%"/>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        , document.querySelector('body'));
}

export default BigImage;
