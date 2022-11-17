//#start of Анимация цифр (блок, начало, конец, длительность)
const counterAnim = (qSelector, start = 0, end, duration = 1000) => {
    const target = document.querySelector(qSelector);
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        target.innerText = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
};
//#end of Анимация цифр

let dataGame
let last_update
let p1lr = 501
let gameData = { tournamentName: "", stage: "" }
let player1GameData = { name: "Игрок 1", require: 501 }
let player2GameData = { name: "Игрок 2", require: 501 }
let tournamentNameId = document.getElementById("tournamentName")
let stageId = document.getElementById("stage")
let gamePlayersCountId = document.getElementById("gamePlayersCount")
let gameNameId = document.getElementById("gameName")
let begin1 = document.getElementById("beginLeg1")
let begin2 = document.getElementById("beginLeg2")
let np1 = document.getElementById("namePlayer1")
    // let np1s=document.getElementById("namePlayer1Stat")    
let rp1 = document.getElementById("requirePlayer1")
let sp1 = document.getElementById("scorePlayer1")
let sets1 = document.getElementById("setsPlayer1")
let legs1 = document.getElementById("legsPlayer1")
let ap1 = document.getElementById("avgPlayer1")
let a9p1 = document.getElementById("avg9Player1")
let c1801 = document.getElementById("c180Player1")
let c1401 = document.getElementById("c140Player1")
let c1001 = document.getElementById("c100Player1")
let adl1 = document.getElementById("adlPlayer1")
let acheck1 = document.getElementById("acheckPlayer1")
let np2 = document.getElementById("namePlayer2")
    // let np2s=document.getElementById("namePlayer2Stat")    
let rp2 = document.getElementById("requirePlayer2")
let sp2 = document.getElementById("scorePlayer2")
let sets2 = document.getElementById("setsPlayer2")
let legs2 = document.getElementById("legsPlayer2")
let ap2 = document.getElementById("avgPlayer2")
let a9p2 = document.getElementById("avg9Player2")
let c1802 = document.getElementById("c180Player2")
let c1402 = document.getElementById("c140Player2")
let c1002 = document.getElementById("c100Player2")
let adl2 = document.getElementById("adlPlayer2")
let acheck2 = document.getElementById("acheckPlayer2")
let hDA = document.getElementById("hiddenDivAvg")

function getScore() {
    $.post("getscore.php", { id: id, last_update: last_update }, function(data) {
        if (data == 'no data') {}
        if (data != 'no data') {
            player1GameData.name = data.player1Name
            player1GameData.require = data.player1Require
            player1GameData.lastRequire
            player1GameData.score = data.player1Score
            player1GameData.dartsThrown = data.player1DartsThrown
            player1GameData.avg = data.player1Avg
            player1GameData.avg9 = data.player1First9Avg
            player1GameData.c180 = data.player1Score180
            player1GameData.c140 = data.player1Score140
            player1GameData.c100 = data.player1Score100
            player1GameData.adl = data.player1AllDartsLegs
            player1GameData.acheck = data.player1AllCheck
            player1GameData.legs = data.player1Legs
            player1GameData.sets = data.player1Sets
            player2GameData.name = data.player2Name
            player2GameData.require = data.player2Require
            player2GameData.lastRequire
            player2GameData.score = data.player2Score
            player2GameData.dartsThrown = data.player2DartsThrown
            player2GameData.avg = data.player2Avg
            player2GameData.avg9 = data.player2First9Avg
            player2GameData.c180 = data.player2Score180
            player2GameData.c140 = data.player2Score140
            player2GameData.c100 = data.player2Score100
            player2GameData.adl = data.player2AllDartsLegs
            player2GameData.acheck = data.player2AllCheck
            player2GameData.legs = data.player2Legs
            player2GameData.sets = data.player2Sets
            gameData.legBegin = data.legBegin
            gameData.throwCurrent = data.throwCurrent
            gameData.tournamentName = data.tournamentName
            gameData.stage = data.stage
            gameData.gamePlayersCount = data.gamePlayersCount
            gameData.gameName = data.gameName
            gameData.gameType = data.gameType
            last_update = data.last_update
            replaceGameData()
        }
    }, "json");

}

function replaceGameData() {
    tournamentName.innerHTML = gameData.tournamentName
    stage.innerHTML = gameData.stage
    gamePlayersCount.innerHTML = gameData.gamePlayersCount
    gameName.innerHTML = gameData.gameName
    if ((player1GameData.sets || player2GameData.sets) > 0) {
        sets1.innerHTML = player1GameData.sets
        sets2.innerHTML = player2GameData.sets
    }
    legs1.innerHTML = player1GameData.legs
    legs2.innerHTML = player2GameData.legs
    if (gameData.throwCurrent == 1) {
        np1.innerHTML = player1GameData.name
        document.getElementById("throwPlayer1").innerHTML = '<i class="bi bi-caret-right" style="color: red;"></i>&nbsp;'
        document.getElementById("throwPlayer2").innerHTML = ''
        np2.innerHTML = player2GameData.name
        if ((player1GameData.require && player2GameData.require) == 501) {
            rp1.innerHTML = '<font color="green">' + player1GameData.require + '</font>'
            rp2.innerHTML = player2GameData.require
        } else {
            counterAnim("#requirePlayer2", player2GameData.lastRequire, player2GameData.require, 500)
            player2GameData.lastRequire = player2GameData.require
                // console.log(player2GameData.lastRequire)
            rp1.innerHTML = '<font color="green">' + player1GameData.require + '</font>'
        }

    } else if (gameData.throwCurrent == 2) {
        np1.innerHTML = player1GameData.name
        np2.innerHTML = player2GameData.name
        document.getElementById("throwPlayer2").innerHTML = '&nbsp;<i class="bi bi-caret-left" style="color: red;"></i>'
        document.getElementById("throwPlayer1").innerHTML = ''
        if ((player1GameData.require && player2GameData.require) == 501) {
            rp1.innerHTML = player1GameData.require
            rp2.innerHTML = '<font color="green">' + player2GameData.require + '</font>'
        } else {
            counterAnim("#requirePlayer1", player1GameData.lastRequire, player1GameData.require, 500)
            player1GameData.lastRequire = player1GameData.require
                // console.log(player1GameData.lastRequire)
            rp2.innerHTML = '<font color="green">' + player2GameData.require + '</font>'
        }
    } else {
        np1.innerHTML = player1GameData.name
        np2.innerHTML = player2GameData.name
        rp1.innerHTML = player1GameData.require
        rp2.innerHTML = player2GameData.require
        document.getElementById("throwPlayer1").innerHTML = ''
        document.getElementById("throwPlayer2").innerHTML = ''
    }

    if (gameData.legBegin == 1) {
        begin1.innerHTML = '<i class="bi bi-circle-fill" style="color: red;"></i>&nbsp;'
        begin2.innerHTML = ''
    } else {
        begin1.innerHTML = ''
        begin2.innerHTML = '&nbsp;<i class="bi bi-circle-fill" style="color: red;"></i>'
    }

    if (player1GameData.dartsThrown == "0" && player2GameData.dartsThrown == "0") {
        sp1.innerHTML = '<img src="/img/1dart150.png" width="30" height="30">' + player1GameData.dartsThrown
        sp2.innerHTML = '<img src="/img/1dart150.png" width="30" height="30">' + player2GameData.dartsThrown
    } else if (player1GameData.score == "" && player2GameData.score >= 0) {
        sp1.innerHTML = '<img src="/img/1dart150.png" width="30" height="30">' + player1GameData.dartsThrown
        sp2.innerHTML = '<img src="/img/1dart150.png" width="30" height="30">' + player2GameData.dartsThrown + '&nbsp;<img src="/img/3dart150.png" width="30" height="30">' + player2GameData.score
    } else if (player2GameData.score == "" && player1GameData.score >= 0) {
        sp1.innerHTML = '<img src="/img/3dart150.png" width="30" height="30">' + player1GameData.score + '&nbsp;<img src="/img/1dart150.png" width="30" height="30">' + player1GameData.dartsThrown
        sp2.innerHTML = '<img src="/img/1dart150.png" width="30" height="30">' + player2GameData.dartsThrown
    }
    ap1.innerHTML = player1GameData.avg
    a9p1.innerHTML = player1GameData.avg9
    c1801.innerHTML = player1GameData.c180
    c1401.innerHTML = player1GameData.c140
    c1001.innerHTML = player1GameData.c100

    let strAdl1 = '';
    for (let i = 0; i < player1GameData.adl.length; i++) {
        if (player1GameData.adl[i] !== undefined) strAdl1 += '<span> ' + player1GameData.adl[i] + '</span>';
    }
    adl1.innerHTML = strAdl1

    let strAcheck1 = '';
    for (let i = 0; i < player1GameData.acheck.length; i++) {
        if (player1GameData.acheck[i] !== undefined) strAcheck1 += '<span> ' + player1GameData.acheck[i] + '</span>';
    }
    acheck1.innerHTML = strAcheck1
    ap2.innerHTML = player2GameData.avg
    a9p2.innerHTML = player2GameData.avg9
    c1802.innerHTML = player2GameData.c180
    c1402.innerHTML = player2GameData.c140
    c1002.innerHTML = player2GameData.c100

    let strAdl2 = '';
    for (let i = 0; i < player2GameData.adl.length; i++) {
        strAdl2 += '<span>' + player2GameData.adl[i] + ' </span>';
    }
    adl2.innerHTML = strAdl2

    let strAcheck2 = '';
    for (let i = 0; i < player2GameData.acheck.length; i++) {
        if (player2GameData.acheck[i] !== undefined) strAcheck2 += '<span>' + player2GameData.acheck[i] + ' </span>';
    }
    acheck2.innerHTML = strAcheck2
}

getScore()
setInterval(getScore, 2000)


var noSleep = new NoSleep();
var wakeLockEnabled = false;
var toggleEl = document.querySelector("#toggle");
toggleEl.addEventListener('click', function() {
    if (!wakeLockEnabled) {
        noSleep.enable(); // keep the screen on!
        wakeLockEnabled = true;
        toggleEl.value = "Переход в спящий режим: отключен!";
        // document.body.style.backgroundColor = "green";
    } else {
        noSleep.disable(); // let the screen turn off.
        wakeLockEnabled = false;
        toggleEl.value = "Переходить в спящий режим: включено";
        //  document.body.style.backgroundColor = "";
    }
}, false);