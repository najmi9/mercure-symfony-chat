import React from 'react';
import className from '../utils/classNames';

const TextAreaField = React.forwardRef(({help, name, children, error, handleKeyDown, required, minLength, ...props}, ref) => {
    if (error) {
        help = error
    }

    return(
        <div className={className("form-group", error && 'has-error')}>
            <label className="control-label" htmlFor={name}>{children}</label>
            <textarea 
                name={name} 
                ref={ref} 
                id={name} 
                className="form-control" 
                required={required}
                minLength={minLength}
                onKeyDown={handleKeyDown} 
                {...props}
            />
            {help && <div className="help-block"> {help} </div>}
        </div>
    );
});

export default TextAreaField;