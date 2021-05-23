import { Picker } from 'emoji-mart';
import React from 'react';

const PickerEmoji = ({addEmoji}) => {
    const style = {};
    const div = document.querySelector('div.msgs');

    if (div && div.scrollHeight > 662) {
        style.top = -350 + 'px';
    }

    return (
        <div className="emojis" style={style}>
            <Picker onSelect={addEmoji} set='facebook' 
            tooltip={false}
            theme="dark"
            showPreview={false}
            showSkinTones={false}
            style={{
                width: 300 + 'px',
            }}
            />
        </div>
    );
}

export default PickerEmoji;
