import {ChangeEvent, FormEvent, useState} from "react";

export const useForm = <Values extends object>(onSubmitCallback: () => unknown, initialState: Values) => {
    const [values, setValues] = useState<Values>(initialState);
    const onChange = (event: ChangeEvent<HTMLInputElement>) => {
        setValues({ ...values, [event.target.name]: event.target.value });
    }
    const onSelectChange = (event: ChangeEvent<HTMLSelectElement>) => {
        const selectedValues: string[] = []
        for (let i = 0; i < event.target.selectedOptions.length; i++) {
            const option = event.target.selectedOptions.item(i)
            if (!option) continue
            selectedValues.push(option.value)
        }
        setValues({ ...values, [event.target.name]: selectedValues })
    }
    const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        await onSubmitCallback(); // triggering the callback
    }
    const reset = () => {
        setValues(initialState)
    }

    return {
        onChange,
        onSelectChange,
        onSubmit,
        values,
        reset,
    };

}
