import {Sequelize} from "sequelize";

const sequelize = new Sequelize('wordpress', 'root', 'password', {
    host: 'localhost',
    dialect: 'mysql',
    logging: false,
    define: {
        "underscored": true
    },
});

try {
    await sequelize.authenticate();
    console.log('Connection has been established successfully.');
} catch (error) {
    console.error('Unable to connect to the database:', error);
}

export default sequelize;
