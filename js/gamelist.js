// Функция изменения отображения в зависимости от выбранного селектора для Оператора

const iconListView1 = document.querySelectorAll('.iconListView1')
const iconListView2 = document.querySelectorAll('.iconListView2')
let viewSetView = true;
let toggleSetView = document.querySelector('#toggleSetView')


toggleSetView.addEventListener('click', function() {
    if (!viewSetView) {
        viewSetView = true
        iconListView1.forEach(element => {
            element.setAttribute = 'hidden'
        })
        
        iconListView2.forEach(element => {
            element.removeAttribute = 'hidden'
        })
        // toggleSetView.value = 'Показ включен'
        // getViewVideo(id)
        // document.getElementsByClassName("iconListView1").hidden = true
        // document.getElementsByClassName("iconListView2").hidden = true
        // document.getElementsByClassName("iconListView3").hidden = false
        // document.getElementsByClassName("iconListView4").hidden = false
    } else {
        viewSetView = false

        viewSetView = true
        iconListView1.forEach(element => {
            element.removeAttribute = 'hidden'
        })
        
        iconListView2.forEach(element => {
            element.setAttribute = 'hidden'
        })


        // toggleSetView.value = 'Показ выключен'
        // getViewVideo(id)
        // document.getElementsByClassName("iconListView1").hidden = false
        // document.getElementsByClassName("iconListView2").hidden = false
        // document.getElementsByClassName("iconListView3").hidden = true
        // document.getElementsByClassName("iconListView4").hidden = true
    }
}, false )

