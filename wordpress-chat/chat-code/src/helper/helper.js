import con from "../config/db.js"
import crypto from "crypto"
import querystring from "querystring"
import { format } from 'date-fns';
class helper {
  getRecords = async (query) => {
    let result = await con.query(query, {
      replacements: { someValue: 'some desired value' }, // parameters to replace in the query
      type: con.QueryTypes.SELECT // specify the query type (SELECT, UPDATE, DELETE, etc.)
    })
    return result;
  }
  insert = async (query) => {
    let result = await con.query(query, {
      //  replacements: { someValue: 'some desired value' }, // parameters to replace in the query
      type: con.QueryTypes.INSERT // specify the query type (SELECT, UPDATE, DELETE, etc.)
    })

    /* sequelize.query(sqlInsertQuery, {
     replacements: { value1: 'some value', value2: 'another value' }, // parameters to replace in the query
     type: Sequelize.QueryTypes.INSERT // specify the query type as INSERT
   }) */
    return result;
  }
  update = async (query) => {
    con.query(query, {
      // replacements: { value1: 'new value', someValue: 'desired condition' }, // parameters to replace in the query
      type: con.QueryTypes.UPDATE // specify the query type as UPDATE
    })
  }
  encrypttext = async (text) => {

    // Encryption key (for demonstration purposes, you should use a strong key)
    const encryptionKey = "MySecretKey12345"; // 16 characters key

    // Create a cipher object with the chosen algorithm (aes-128-cbc)
    const cipher = crypto.createCipher('aes-128-cbc', encryptionKey);

    // Encrypt the text
    let encryptedText = cipher.update(text, 'utf-8', 'base64');
    encryptedText += cipher.final('base64');

    // URL-safe encode the encrypted text
    const urlSafeText = encodeURIComponent(encryptedText);
    return urlSafeText;
  }
}

export default new helper();