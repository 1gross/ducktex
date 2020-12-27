ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("address", {
            center: [55.716022, 37.456621],
            zoom: 16
        }, {
            searchControlProvider: 'yandex#search'
        }),


    myGeoObject = new ymaps.GeoObject({
        // Описание геометрии.
        geometry: {
            type: "Point",
            coordinates: [55.716022, 37.456621]
        },
        // Свойства.
        properties: {
            // Контент метки.
            iconContent: 'г. Москва, ул. Инициативная, 11',
        }
    }, {
        // Опции.
        // Иконка метки будет растягиваться под размер ее содержимого.
        preset: 'islands#blackStretchyIcon',

    });

    myMap.geoObjects
        .add(myGeoObject);
}
