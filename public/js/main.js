const redirect = (path, timeout = 0) => {
    if(timeout > 0) {
        setTimeout(()=>{
            window.location.href = path;
        }, timeout);
        return;
    }
    window.location.href = path;
}

const reload = (timeout = 0) => {
    setTimeout(()=>{
        window.location.reload();
    }, timeout);
}

const getDataForm = (element, rule = {}) => {
    const params = new FormData();
    const form = $(element).serializeArray();
    const images = $(element).find('input[type=file]');
    for (let i = 0; i < images.length; i++) {
        const image = $(images[i]).prop('files');
        params.append($(images[i]).attr('name'), image.length ? image[0]:'');
    }
    form.forEach((field)=>{
        params.append(field.name, field.value);
    });
    handleRule(params, rule, element);
    return params;
}

const log_form_data = (form) => {
    console.log(Object.fromEntries(form));
}

const validationRule = {
    required: (...val) => {
        return !val[0] ? val[1] : false;
    },
    email: (...val) => {
        return !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val[0]) ? val[1] : false;
    },
    min: (...val) => {
        return val[0] < val[2] ? val[1] : false;
    },
    max: (...val) => {
        return val[0] > val[2] ? val[1] : false;
    }
}

const handleRule = (params, rules, element) =>{
    const convert_params = Object.fromEntries(params);
    var count = 0;
    for (const key in convert_params) {
        const input = $(element).find(`input[name="${key}"]`);
        if(rules[key]){
            for (const key_rule in rules[key]) {
                const key_new = key_rule.split('_');
                const result = validationRule[key_new[0]](input.val(), rules[key][key_rule], key_new[1] ?? '');
                if(result) {
                    count++;
                    input.addClass('border-danger');
                    input.parents().find('.error_'+key).html(result);
                } else {
                    input.removeClass('border-danger');
                    input.parents().find('.error_'+key).html('');
                    count--;
                }
            }
        }
    }
    params.append('invalid', count > 0 ? 1:0);
    return params;
}
