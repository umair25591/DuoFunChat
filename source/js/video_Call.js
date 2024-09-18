import { showNotification, GetAllMessageCountForNotification } from './functions.js';


let localStream;
let remoteStream;
let peerConnection;
var partnerId = null;
let socket;
let socketOpen = false;
let startcallbtn = document.getElementById('startCall');
let endcallbtn = document.getElementById('endCall');
var mic = $(".mic");
var mic_mute = $(".mic_mute");
var speaker_mute = $(".speaker_mute");
var speaker = $(".speaker");
var MyStatus = {
    action: 'none'
}
var connectionStatus = {
    isConnected: false,
};
var friendRequest = {
    status: false,
};
var loginStatus = {
    login: false,
};
var userId = null;
var friend_box = $('.friend_box');
const servers = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }
    ]
};
var roomId = getRoomIdFromUrl();



$(document).ready(function () {
    mic.on('click', function () {
        toggleMicrophone(false);  // Mute the microphone
        mic.hide();
        mic_mute.show();
    });

    mic_mute.on('click', function () {
        toggleMicrophone(true);  // Unmute the microphone
        mic_mute.hide();
        mic.show();
    });

    speaker.on('click', function () {
        toggleSpeaker(false);  // Mute the speaker
        speaker.hide();
        speaker_mute.show();
    });

    speaker_mute.on('click', function () {
        toggleSpeaker(true);  // Unmute the speaker
        speaker_mute.hide();
        speaker.show();
    });
});


const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');

const constraints = {
    video: {
        width: { min: 100, ideal: 320 },
        height: { min: 100, ideal: 240 },
        frameRate: { min: 10, ideal: 10 }
    },
    audio: true
};


function getRoomIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('roomId');
}

function logStreamTracks(stream) {
    if (stream) {
        console.log('Stream ID: ' + stream.id);
        stream.getTracks().forEach(track => {
            console.log(`Track kind: ${track.kind}, enabled: ${track.enabled}`);
        });
    } else {
        console.log('No stream provided.');
    }
}


$.ajax({
    url: "./source/php/checkLogin.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
        // console.log(response);
        if (response.userId) {
            setInterval(GetAllMessageCountForNotification, 5000);
            $('.friend').show();
            friend_box.show();
            loginStatus.login = true;
            userId = response.userId;
            if (roomId) {
                connectWithRoom();
            }
            else {
                startcallbtn.style.display = "block";
                endcallbtn.style.display = "none";
            }
        } else {
            friend_box.hide();
            loginStatus.login = false;
            userId = null;
            startcallbtn.style.display = "block";
        }
    },
    error: function (xhr, status, error) {
        console.error("Error checking login status:", error);
        $("#status").text("Error checking login status");
    },
});


function getLocalStream() {
    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            localVideo.srcObject = stream;
            localStream = stream;
            // console.log('Local stream obtained');
        })
        .catch(error => {
            showNotification("Allow camera access to start call");
            console.error('Error accessing media devices.', error);
            console.log('Error accessing media devices: ' + error.message);
        });
}

function initializeSocket() {

    var url = "wss://duofunchat.com:8080?type=video";

    if (loginStatus.login === true) {
        url += "&userId=" + encodeURIComponent(userId);
    }

    socket = new WebSocket(url);


    socket.onopen = () => {
        socketOpen = true;
        startcallbtn.style.display = 'none';
        endcallbtn.style.display = 'block';
    };

    socket.onerror = error => {
        console.log('WebSocket error', error);
        console.log('WebSocket error: ' + (error.message || 'Undefined error'));
    };

    socket.onmessage = message => {
        const data = JSON.parse(message.data);
        // console.log('Received message: ' + JSON.stringify(data, null, 2));
        if (data.action === "paired" && data.partnerId != null) {
            $('.friend').show();
            $('.add_friend').hide();
            partnerId = data.partnerId
            friendRequest.status = true;
            connectionStatus.isConnected = true;
            if (data.mode === "caller") {
                MyStatus.action = 'caller';
                startCall();
            }
            else if (data.mode === "receiver") {
                MyStatus.action = 'receiver';
            }
        }

        if (data.offer) {
            handleOffer(data.offer);
        }
        else if (data.ice) {
            handleICECandidate(data.ice);
        }
        else if (data.answer) {
            handleAnswer(data.answer);
        }
        else if (data.connection === "CoNneCtIoN ClOsEd") {
            endCall();
        }
    };
    socket.onclose = message => {
        endCall();
        startcallbtn.style.display = 'block';
        endcallbtn.style.display = 'none';
        MyStatus.action = 'none';
    }
}

getLocalStream();



function connectWithRoom() {
    if (roomId) {
        $('.friend').hide();
        $('.add_friend').hide();
        if (userId !== null) {
            socket = new WebSocket(`wss://duofunchat.com:8080?type=video&roomId=${roomId}&userId=${encodeURIComponent(userId)}`);
            socket.onopen = () => {
                socketOpen = true;
                console.log('WebSocket connection established');
            };

            socket.onerror = error => {
                console.log('WebSocket error', error);
                console.log('WebSocket error: ' + (error.message || 'Undefined error'));
            };

            socket.onmessage = message => {
                const data = JSON.parse(message.data);
                console.log('Received message: ' + JSON.stringify(data, null, 2));
                if (data.action === "paired" && data.partnerId != null) {
                    partnerId = data.partnerId
                    connectionStatus.isConnected = true
                    if (data.mode === "caller") {
                        MyStatus.action = 'caller';
                        startCall();
                    }
                    else if (data.mode === "receiver") {
                        MyStatus.action = 'receiver';
                    }
                    endcallbtn.style.display = "block";
                }

                if (data.offer) {
                    handleOffer(data.offer);
                }
                else if (data.ice) {
                    handleICECandidate(data.ice);
                }
                else if (data.answer) {
                    handleAnswer(data.answer);
                }
                else if (data.connection === "CoNneCtIoN ClOsEd") {
                    endCall();
                }
                else if (data.status === "UsEr Id NoT FoUnD") {
                    var body = $('body');
                    body.empty()
                    body.html("Please Login First");
                }
            };
            socket.onclose = message => {
                endCall();
                MyStatus.action = 'none';
                console.log("connection closed");
            }
        }
        else {
            console.log("user isnt login");
        }
    }
    else {
        startcallbtn.style.display = "block";
        endcallbtn.style.display = "none";
    }
}

startcallbtn.style.display = "none";
endcallbtn.style.display = "none";

startcallbtn.addEventListener('click', initializeSocket);
endcallbtn.addEventListener('click', endCall);

function sendSignal(message) {
    if (socketOpen) {
        socket.send(JSON.stringify(message));
    } else {
        console.log('WebSocket connection is not open');
    }
}

function startCall() {
    if (!localStream) {
        showNotification('Please allow camera access to start call');
        return;
    }

    if (!socketOpen) {
        console.log('WebSocket connection is not open');
        return;
    }

    if (peerConnection) {
        console.log('Peer connection already exists');
        return;
    }

    // console.log('Starting call with status:', MyStatus.action);

    peerConnection = new RTCPeerConnection(servers);

    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

    peerConnection.ontrack = event => {
        const [stream] = event.streams;
        remoteVideo.srcObject = stream;
        remoteStream = stream;
        // console.log('Remote stream received');
    };

    if (MyStatus.action === "caller") {
    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            sendSignal({ 'ice': event.candidate, 'partnerId': partnerId });
        }
    };

    peerConnection.createOffer()
        .then(offer => {
            // console.log('Offer created:', offer);
            return peerConnection.setLocalDescription(offer);
        })
        .then(() => {
            sendSignal({ 'offer': peerConnection.localDescription, 'partnerId': partnerId });
        })
        .catch(error => {
            console.log('Error creating offer: ' + error.message);
        });
    } else if (MyStatus.action === "receiver") {
        console.log("I am receiver, waiting for an offer");
    }
}


function endCall() {
    friendRequest.status = false;
    connectionStatus.isConnected = false;
    $('.friend').show();
    $('.add_friend').hide();
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
    remoteVideo.srcObject = null;
    remoteStream = null;
    if (socketOpen) {
        socket.close();
    }
    else {
        // console.log("websocket isnt open")
    }
    // console.log('Call ended');
}

function handleOffer(offer) {
    if (!peerConnection) {
        startCall();
    }
    peerConnection.setRemoteDescription(new RTCSessionDescription(offer))
        .then(() => {
            return peerConnection.createAnswer();
        })
        .then(answer => {
            return peerConnection.setLocalDescription(answer);
        })
        .then(() => {
            sendSignal({ 'answer': peerConnection.localDescription, 'partnerId': partnerId });
            // console.log('Answer created: ' + JSON.stringify(peerConnection.localDescription));
        })
        .catch(error => {
            console.error('Error handling offer.', error);
            console.log('Error handling offer: ' + error.message);
        });
}

function handleAnswer(answer) {
    peerConnection.setRemoteDescription(new RTCSessionDescription(answer))
        .then(() => {
            // console.log('Answer received and set: ' + JSON.stringify(answer));
        })
        .catch(error => {
            console.error('Error setting remote description from answer.', error);
            console.log('Error setting remote description from answer: ' + error.message);
        });
}

function handleICECandidate(candidate) {
    peerConnection.addIceCandidate(new RTCIceCandidate(candidate))
        .then(() => {
            // console.log('ICE candidate added: ' + JSON.stringify(candidate));
        })
        .catch(error => {
            console.error('Error adding ICE candidate.', error);
            console.log('Error adding ICE candidate: ' + error.message);
        });
}
function toggleMicrophone(enable) {
    if (localStream) {
        localStream.getAudioTracks().forEach(track => {
            track.enabled = enable;
            showNotification(`Microphone ${enable ? 'unmuted' : 'muted'}`);
        });
    }
}

function toggleSpeaker(enable) {
    if (remoteVideo) {
        remoteVideo.muted = !enable;
        showNotification(`Speaker ${enable ? 'unmuted' : 'muted'}`);
    }
}


$('.friend').off('click').on("click", function (e) {
    e.preventDefault();
    if (connectionStatus.isConnected === true) {
        if (friendRequest.status === true) {
            $.ajax({
                url: "./source/php/sendFriendRequest.php",
                method: "POST",
                dataType: "json",
                data: {
                    userId: userId,
                    partnerId: partnerId
                },
                success: function (response) {
                    if (response.message === "Friend request sent successfully.") {
                        showNotification(response.message);
                        friendRequest.status = false;
                        $('.friend').hide();
                        $('.add_friend').show();
                    }
                    else if (response.message === "Request already exists !") {
                        showNotification(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("AJAX error - Status:", status, "Error:", error);
                    showNotification(xhr.responseText);
                },
            });
        }
        else {
            showNotification("Friend Request sent already !");
        }
    }
    else {
        showNotification("No User Found");
    }
});