$(document).ready(function () {
  init()
})

var socket

function init () {
  var host = 'ws://172.30.0.185:9000/echobot'

  try {
    socket = new WebSocket(host)
    socket.onopen = onConnectionSuccess;
    socket.onmessage = onMessageReceived;
  } catch (ex) {
    log(ex)
  }

  $('.main-text-input-centered').focus()
}

function quit () {
  if (socket != null) {
    log('Goodbye!')
    socket.close()
    socket = null
  }
}

function log (text) {
  $('.game-console').html($('.game-console').html() + '<br>' + text)
}

$('#word-input').keypress(function (e) {
  if (e.keyCode === 13) {
    send('word-input')
    $('#word-input').prop('disabled', true)
  }
})
