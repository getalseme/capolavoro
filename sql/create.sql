CREATE DATABASE school_resources;

USE school_resources;

CREATE TABLE users(
    email VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100)  NOT NULL,
    surname VARCHAR(100) NOT NULL,
    password CHAR(64) NOT NULL
);

CREATE TABLE student(
    id INT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(email) 
);

CREATE TABLE teacher(
    id INT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(email) 
);

CREATE TABLE admin(
    id INT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(email) 
);

CREATE TABLE unavaileble(
    id INT PRIMARY KEY,
    start DATETIME NOT NULL,
    end DATETIME NOT NULL,
    description VARCHAR(255) NOT NULL,
    user_id VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(email) 
);

CREATE TABLE class(
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    creation DATETIME NOT NULL
);

CREATE TABLE student_in_class(
    class_id INT,
    student_id INT,
    FOREIGN KEY (class_id) REFERENCES class(id),
    FOREIGN KEY (student_id) REFERENCES student(id),
    PRIMARY KEY (class_id, student_id)
);

CREATE TABLE unavaileble_class(
    unavaileble_id INT,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES class(id),
    FOREIGN KEY (unavaileble_id) REFERENCES unavaileble(id),
    PRIMARY KEY (unavaileble_id, class_id)
);

CREATE TABLE activity(
    name VARCHAR(50) PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    public BOOLEAN NOT NULL,
    teacher_id INT NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teacher(id)
);

CREATE TABLE class_activity(
    class_id INT,
    activity_id VARCHAR(50),
    FOREIGN KEY (class_id) REFERENCES class(id),
    FOREIGN KEY (activity_id) REFERENCES activity(name),
    PRIMARY KEY (activity_id, class_id)
);

CREATE TABLE room(
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    site INT NOT NULL,
    number INT
);

CREATE TABLE schedule_time(
    id INT PRIMARY KEY,
    day INT NOT NULL,
    hour INT NOT NULL
);

CREATE TABLE day_time(
    id INT PRIMARY KEY,
    date DATE NOT NULL,
    hour INT NOT NULL
);

CREATE TABLE booking(
    day_time_id INT,
    teacher_id INT NOT NULL,
    room_id INT,
    activity_id VARCHAR(50),
    reason VARCHAR(255) NOT NULL, 
    FOREIGN KEY (day_time_id) REFERENCES day_time(id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(id),
    FOREIGN KEY (room_id) REFERENCES room(id),
    FOREIGN KEY (activity_id) REFERENCES activity(name),
    PRIMARY KEY (room_id, day_time_id)
);

CREATE TABLE schedule(
    schedule_time_id INT,
    teacher_id INT,
    room_id INT,
    class_id INT NOT NULL,
    subject VARCHAR(20) NOT NULL,
    FOREIGN KEY (schedule_time_id) REFERENCES schedule_time(id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(id),
    FOREIGN KEY (room_id) REFERENCES room(id),
    FOREIGN KEY (class_id) REFERENCES class(id),
    PRIMARY KEY (schedule_time_id, teacher_id, class_id)
);



DELIMITER //

CREATE PROCEDURE get_free_rooms(IN input_day INT, IN input_hour INT)
BEGIN
    SELECT DISTINCT view_schedule.room, room.id 
    FROM view_schedule
    JOIN room ON view_schedule.room = room.name
    WHERE view_schedule.room NOT IN (
        SELECT view_schedule.room 
        FROM view_schedule
        INNER JOIN schedule_time ON schedule_time.id = view_schedule.idTime
        WHERE schedule_time.day = input_day AND schedule_time.hour = input_hour
    )
    ORDER BY view_schedule.room ASC;
END //

DELIMITER ;