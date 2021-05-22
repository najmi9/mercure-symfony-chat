import { Picker } from 'emoji-mart';
import React from 'react';

const PickerEmoji = ({addEmoji}) => {

    return (
        <div className="emojis">
            <Picker onSelect={addEmoji} />
        </div>
    );
}

export default PickerEmoji;
