export const moneyFormat = value => {
    value = Number(value);
    return `${value.toFixed(2)} zł`.replace(".", ",");
}
