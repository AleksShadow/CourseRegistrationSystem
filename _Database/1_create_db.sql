-- Database CST8257: Student and Course Registration System
-- Created by Aleks Tselikovskii

CREATE DATABASE CST8257;
USE CST8257;

-- Table structure for table 'Student'
DROP TABLE IF EXISTS Student;
CREATE TABLE Student (
  StudentId varchar(16) NOT NULL PRIMARY KEY,
  Name varchar(256) NOT NULL,
  Phone varchar(16),
  Password varchar(256)
);

-- Table structure for table 'Course'
DROP TABLE IF EXISTS Course;
CREATE TABLE Course (
  CourseCode varchar(10) NOT NULL PRIMARY KEY,
  Title varchar(256) NOT NULL,
  WeeklyHours int NOT NULL
);

-- Table structure for table 'Semester'
DROP TABLE IF EXISTS Semester;
CREATE TABLE Semester (
  SemesterCode varchar(10) NOT NULL PRIMARY KEY,
  Term varchar(10) NOT NULL,
  Year int NOT NULL
);

-- Table structure for table 'CourseOffer'
DROP TABLE IF EXISTS CourseOffer;
CREATE TABLE CourseOffer (
  CourseCode varchar(10) NOT NULL,
  SemesterCode varchar(10) NOT NULL,
  PRIMARY KEY (CourseCode, SemesterCode),
  FOREIGN KEY (SemesterCode) REFERENCES Semester(SemesterCode),
  FOREIGN KEY (CourseCode) REFERENCES Course(CourseCode)
);

-- Table structure for table 'Registration'
DROP TABLE IF EXISTS Registration;
CREATE TABLE Registration (
  StudentId varchar(16) NOT NULL,
  CourseCode varchar(10) NOT NULL,
  PRIMARY KEY (StudentId, CourseCode),
  SemesterCode varchar(10) NOT NULL,
  FOREIGN KEY (StudentId) REFERENCES Student(StudentId),
  FOREIGN KEY (CourseCode) REFERENCES CourseOffer (CourseCode),
  FOREIGN KEY (SemesterCode) REFERENCES CourseOffer (SemesterCode)
);
