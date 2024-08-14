import express from 'express';
import https from 'https';
import http from 'http';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';
import fs from 'fs';
import chatController from "./controllers/chatController.js";
import db from "./config/db.js";
import { Server } from "socket.io";
import cors from "cors";
import bodyParser from "body-parser";


const app = express();
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);


// Configure your routes and middleware
app.get('/', (req, res) => {
    res.send('Hello, HTTPS!');
});

// Read the SSL certificate and private key files

//const privateKey = fs.readFileSync('/home/dell/ssl/privkey.pem', 'utf8');
//const certificate = fs.readFileSync('/home/ssl/fullchain.pem', 'utf8');

// Create the HTTPS server

//const credentials = { key: privateKey, cert: certificate };
//const httpsServer = https.createServer(credentials, app);
 const httpsServer = http.createServer(app);

// app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.set("view engine", "ejs");
let pathUrl = join(__dirname, "views");

app.set("views", pathUrl);

app.get("/messages/:senderId/:receiverId", (req, res) => {
    res.render("room", { senderId: req.params.senderId, receiverId: req.params.receiverId });
});
app.get("/group/:senderId/:groupId", (req, res) => {
    res.render("grouproom", { senderId: req.params.senderId, groupId: req.params.groupId });
});

app.get("/separate/:senderId/:type", (req, res) => {
    res.render("separate", { senderId: req.params.senderId, type: req.params.type });
});

/* app.get("/createqrcode", async (req, res) => {
  
   let fileName="WYJ7WNVD_1695023129685.png";
  
   let output=await chatController.uploadFileToS3(fileName,fileName)
   
}); */

app.get("/sendemailtest", async () => {

    //("------------------------------------------------------------------");
    const res = await chatController.sendEmailSendgrid("123");


});
app.get("/testdbconnection", async (req, res) => {

    console.log("*********************************************************");
    const result = await chatController.testdbconnection("123");
    res.send(result)
    console.log(result)

});

const io = new Server(httpsServer, {
    cors: {
        origin: '*',
    }
});
let userIdArray = []
let roomWiseData = [];
let userListArray = [];
let joinRoom = [];
let newMessageArray = [];
let connectArray = [];
let chatDetailArray = [];
let timeForNewRequest = 1;
io.on('connection', (socket) => {

    socket.on('CONNECT', async (senderId) => {
        if (connectArray[senderId] == '') {
            connectArray[senderId] = new Date();
        }
        else {
            const currentTime = new Date();
            let timeDifferenceInSeconds = (currentTime - connectArray[senderId]) / 1000;
            if (timeDifferenceInSeconds < timeForNewRequest) {
                console.log("return");
                return true;
            }
            else {
                connectArray[senderId] = currentTime;
            }
        }

        await chatController.onlineOrOffline(senderId, "online");
    });
    socket.on('JOIN_ROOM', async (senderId, receiverId) => {
        try {
            console.log("senderId, receiverId", senderId, receiverId);
            if (joinRoom[senderId] == '') {
                joinRoom[senderId] = new Date();
            }
            else {
                const currentTime = new Date();
                let timeDifferenceInSeconds = (currentTime - joinRoom[senderId]) / 1000;
                if (timeDifferenceInSeconds < timeForNewRequest) {
                    console.log("return");
                    return true;
                }
                else {
                    joinRoom[senderId] = currentTime;
                }
            }
            let roomId = Math.floor(Math.random() * (9999 - 1000) + 1000) + Date.now();
            userIdArray[socket.id] = senderId;

            const res = await chatController.createRoom(roomId, senderId, receiverId);
            let data = JSON.parse(res);
            //get orgRoomId
            roomId = data.data.room_id;
            roomWiseData[socket.id] = { receiverId: receiverId, senderId: senderId, roomId: roomId };
            socket.join(roomId);
            socket.join("stdRoom" + senderId);
            //  await chatController.onlineOrOffline(senderId, "online");
            io.to(roomId).emit('JOIN_ROOM_RESPONSE', res);
        } catch (error) {
            console.log(error.message)
        }
    });
    socket.on('NEW_MESSAGE', async (data) => {
        console.log("3")
        try {
            console.log("data", data)
            let newData = JSON.parse(data);//JSON.parse(data);

            const roomId = newData.roomId;
            const sender_id = newData.senderId;
            const receiver_id = newData.receiverId;
            const message = newData.message;
            const type = 'text'; // 'text', 'image', 'file'
            // const type = newData.type;
            console.log("roomId", roomId)
            console.log("sender_id", sender_id)
            console.log("receiver_id", receiver_id)
            console.log("message", message)

            if (newMessageArray[sender_id] == '') {
                newMessageArray[sender_id] = new Date();
            }
            else {
                const currentTime = new Date();
                let timeDifferenceInSeconds = (currentTime - newMessageArray[sender_id]) / 1000;
                if (timeDifferenceInSeconds < timeForNewRequest) {
                    console.log("return");
                    return true;
                }
                else {
                    newMessageArray[sender_id] = currentTime;
                }
            }


            let respons = await chatController.sendMessage(roomId, sender_id, receiver_id, message, type);
            io.to(roomId).emit('SEND_MESSAGE_RESPONSE', respons);
            socket.join("stdRoom" + receiver_id);
            const res2 = await chatController.userList(receiver_id);
            io.to("stdRoom" + receiver_id).emit('USER_LIST_RESPONSE', res2)

            socket.join("stdRoom" + sender_id);
            const res4 = await chatController.userList(sender_id);
            io.to("stdRoom" + sender_id).emit('USER_LIST_RESPONSE', res4)
        } catch (error) {
            console.log(error.message)
        }
    })
    socket.on('USER_LIST', async (user_id) => {
        console.log("calluserlist", user_id)
        socket.join("stdRoom" + user_id);
        if (userListArray[user_id] == '') {
            userListArray[user_id] = new Date();
        }
        else {
            const currentTime = new Date();
            let timeDifferenceInSeconds = (currentTime - userListArray[user_id]) / 1000;
            if (timeDifferenceInSeconds < timeForNewRequest) {
                console.log("return from userlist", user_id);
                return true;
            }
            else {
                userListArray[user_id] = currentTime;
            }
        }
        const res = await chatController.userList(user_id);
        console.log("res****************************", res);
        // io.to(roomId).emit('SEND_MESSAGE_RESPONSE', respons);
        io.to("stdRoom" + user_id).emit('USER_LIST_RESPONSE', res)
    })
    socket.on('CHAT_DETAIL_NEW', async (data) => {
        console.log("CHAT_DETAIL_NEW", data)
        // if (chatDetailArray[data.senderId] == '') {
        //     chatDetailArray[data.senderId] = new Date();
        // }
        // else {
        //     const currentTime = new Date();
        //     let timeDifferenceInSeconds = (currentTime - chatDetailArray[data.senderId]) / 1000;
        //     if (timeDifferenceInSeconds < timeForNewRequest) {
        //         console.log("resturn");
        //         return true;
        //     }
        //     else {
        //         chatDetailArray[data.senderId] = currentTime;
        //     }
        // }
        console.log("5")
        try {
            // console.log("chatDetailCalled", data.senderId)
            data = JSON.parse(data);//JSON.parse(data);
            let roomId = data.roomId;
            let senderId = data.senderId;


            console.log("datadatadata", data);
            const res = await chatController.details(roomId, senderId);
            let temp = JSON.parse(res)
            socket.leave(roomId);
            socket.join(roomId);

            io.to(roomId).emit('CHAT_DETAIL_RESPONSE_NEW', res);
            console.log("res******************CHAT_DETAIL_NEW*********", res);

            const getSenderSocet = await chatController.firstUser(senderId);
            let userdata = JSON.parse(getSenderSocet);
            const res2 = await chatController.userList(senderId);
            // io.to(userdata.data.socket_id).emit('USER_LIST_RESPONSE', res2)
            io.to("stdRoom" + senderId).emit('USER_LIST_RESPONSE', res2)



        } catch (error) {
            console.log("error", error.message);
        }

    })

    socket.on('disconnect', async () => {

        try {
            let userId = userIdArray[socket.id];
            let roomData = roomWiseData[socket.id]
            if (userId && roomData && roomData.roomId) {
                await chatController.onlineOrOffline(userId, "offline");
                // const res = await chatController.details(roomData.roomId, roomData.senderId, 0,'');
                // socket.join(roomData.roomId);

                // io.to(roomData.roomId).emit('CHAT_DETAIL_RESPONSE', res);
            }
        } catch (error) {

        }

    });
    socket.on('disconnected', () => {
        let userId = userIdArray[socket.id]

    })//
});

// Start the HTTPS server
const port = 6754;
httpsServer.listen(port, () => {
       console.log(`HTTPS server is running on port ${port}`);
});
