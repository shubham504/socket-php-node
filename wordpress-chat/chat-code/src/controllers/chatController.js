import con from "../config/db.js"
import { format } from 'date-fns';
import moment from "moment";
import AWS from 'aws-sdk';
import axios from 'axios';
import { promises as fs } from 'fs';
import helper from "../helper/helper.js";
class ChatController {

    onlineOrOffline = async (userId, type) => {
        console.log("555555555555555555555555555555555");
        if (type == "online") {
            await helper.update('update wp_users set is_online="1"  WHERE  id=' + userId + ' ');
        }
        else {
            await helper.update('update wp_users set is_online="0"  WHERE  id=' + userId + ' ');
        }
    }
    createRoom = async (roomId, senderId, receiverId) => {
        const rows = await helper.getRecords('SELECT * FROM `user_chat_rooms` WHERE  ((sender_id = ' + senderId + ' and receiver_id=' + receiverId + ') or (sender_id = ' + receiverId + ' and receiver_id=' + senderId + ') )');

        if (rows.length > 0) {
            return JSON.stringify({
                status: true,
                message: "Room already available.",
                data: rows[0],
            });
        }
        else {
            const recheck = await helper.getRecords('SELECT * FROM `user_chat_rooms` WHERE ((sender_id = ' + senderId + ' and receiver_id=' + receiverId + ') or (sender_id = ' + receiverId + ' and receiver_id=' + senderId + ') )');

            if (recheck.length > 0) {
                return JSON.stringify({
                    status: true,
                    message: "Room already available.",
                    data: recheck[0],
                });
            }

            await helper.insert('insert into  `user_chat_rooms`(sender_id,receiver_id,room_id) values ( ' + senderId + ' , ' + receiverId + ', ' + roomId + ') ');

        }
        let rows1 = await helper.getRecords('SELECT * FROM `user_chat_rooms` WHERE  ((sender_id = ' + senderId + ' and receiver_id=' + receiverId + ') or (sender_id = ' + receiverId + ' and receiver_id=' + senderId + ') )');

        if (rows1) {

            return JSON.stringify({
                status: true,
                message: "Room created successfully.",
                data: rows1[0],
            });
        } else {
            return JSON.stringify({
                status: false,
                message: "Room creating failed.",
            });
        }
    }

    userList = async (userId) => {
         console.log(userId,"userList")
        let query = "select * from user_chat_rooms where  (sender_id='" + userId + "' or receiver_id='" + userId + "')";
        //and (h.last_message != null or h.last_message != '' )

        const rows = await helper.getRecords(query);
        let result = [];
        for (const iterator of rows) {
            let tempObject = {};
            tempObject = iterator;

            let unReadMessageCount = 0;
            let senderId = iterator.sender_id == userId ? iterator.receiver_id : iterator.sender_id;
            const userchat = await helper.getRecords("select * from user_chats where sender_id='" + senderId + "' and receiver_id='" + userId + "' and is_read='0'  ");
            if (userchat.length > 0) {
                unReadMessageCount = userchat.length;
            }




            tempObject.unReadMessageCount = unReadMessageCount;

            tempObject.last_message_time = await this.formatDate(moment(Number(iterator.last_message_timestamp)));
            result.push(tempObject);
        }
        // console.log(result, "result")

        return JSON.stringify({
            status: true,
            message: "User list",
            data: result,
        });


    }
    sendMessage = async (roomId, senderId, receiverId, message, type) => {
        try {

       /*     const rows = await helper.getRecords('SELECT * FROM `block_users` WHERE  user_id=' + senderId + ' and block_user_id=' + receiverId + ' ');
            const blockrow = await helper.getRecords('SELECT * FROM `block_users` WHERE  user_id=' + receiverId + ' and block_user_id=' + senderId + ' ');

            if (rows.length > 0 || blockrow.length > 0) {

                return JSON.stringify({
                    status: false,
                    message: "Blocked User",
                });
            }*/

            let sql = 'insert into  `user_chats`(room_id,sender_id,receiver_id,type,message) values ( ' + roomId + ',' + senderId + ' , ' + receiverId + ',"text", "' + message + '") ';
            await helper.insert(sql);

            let lastMessage = "";
            if (type == "image") {
                lastMessage = "File";
            } else {
                lastMessage = message;
            }
            const currentDate = new Date();
            const dateTime = currentDate.toISOString().slice(0, 19);
                console.log("ewewewew")
            return JSON.stringify({
                status: true,
                message: "Message sent successfully",
                data: [],
            });
        } catch (error) {
            console.error(error.message);
            return JSON.stringify({
                status: false,
                message: "Something went wrong",
            });
        }
    }
    details = async (roomId, senderId) => {
        console.log("roomIdroomIdroomIdroomIdroomIdroomId",roomId)
        let sql = 'SELECT uc.*,s.user_nicename as senderName ,s.user_url as ID,r.user_nicename as receiverName ,r.user_url as ID FROM `user_chats` as uc left join wp_users as s on s.ID=uc.sender_id  left join wp_users as r on r.ID=uc.receiver_id WHERE  room_id=' + roomId + ' order by uc.id asc';

        const userChats = await helper.getRecords(sql);

        await helper.update('update user_chats set is_read="1" WHERE room_id=' + roomId + ' and receiver_id=' + senderId + ' and is_read="0" ');


        if (userChats.length > 0) {
            let result = [];
            let dateArray = [];
            for (const iterator of userChats) {
                let tempObject = {};
                tempObject = iterator;
                let date = moment(iterator.created_at).format('YYYY-MM-DD');;
                if (dateArray.includes(date)) {
                    tempObject.chatDate = '';
                }
                else {
                    dateArray.push(date);
                    tempObject.chatDate = date;
                }
                result.push(tempObject);
            }


            return JSON.stringify({
                status: true,
                message: "Data Available",
                data: result,
            });
        } else {
            return JSON.stringify({
                status: true,
                message: "Data Not Found!",
                data: [],
            });
        }

    }

    firstUser = async (userId) => {

        const rows1 = await helper.getRecords('select * FROM wp_users WHERE id=' + userId + ' ');
        return JSON.stringify({
            status: true,
            message: "getuser successfully.",
            data: rows1[0],
        });
    }


    formatDate = async (date) => {

        const now = moment();
        const diffInDays = now.diff(date, 'days');

        if (diffInDays === 0) {
            // Display time if the date is today
            return date.format('LT');
        } /* else if (diffInDays === 1) {
          return 'Yesterday';
        } */ else {
            return `${diffInDays} days ago`;
        }
    }
    generateRandomString = async (length) => {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            result += characters.charAt(randomIndex);
        }

        return result;
    }
    generateRandomImageName = async (extension) => {
        const randomString = await this.generateRandomString(8); // You can adjust the length as needed
        const timestamp = Date.now();
        return `${randomString}_${timestamp}.${extension}`;
    }
    sendNotification = async (title, body, token, senderId, receiverId) => {
        const fcmUrl = 'https://v1.checkprojectstatus.com/hirdle/api/sendNoticationFromChat';

        const message = {
            token: token,
            body: body,
            title: title,
            senderId: senderId,
            receiverId: receiverId,
        };

        await axios
            .post(fcmUrl, message)
            .then((response) => {
                console.log('Successfully sent message:', response.data);
            })
            .catch((error) => {
                if (error.response) {
                    console.error('Error sending message:', error.response.data);
                } else {
                    console.error('Error sending message:', error.message);
                }
            });
    }
    testdbconnection = async () => {
        const userlist = await helper.getRecords("select * from user_chat_rooms limit 1  ");

        let result = [];
        for (const iterator of userlist) {
            result.push(iterator.user_id);
        }

        return result;
    }
}

export default new ChatController();
