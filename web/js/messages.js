/**
 * Created by slashman on 12.03.15.
 */


function noMoreCameras() {
    sweetAlert('', "По вашему тарифу вы подключили максимальное количество камер . Вы можете сменить тариф чтобы подключить еще камеры", "error");
}

function youAreBlocked() {
    sweetAlert('', "Ваш аккаунт заблокирован!", "error");
}