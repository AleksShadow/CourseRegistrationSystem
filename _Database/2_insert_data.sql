use CST8257;
insert into Course (CourseCode,Title,WeeklyHours) values ('CON8101','Residential Building Estimating',5);
insert into Course (CourseCode,Title,WeeklyHours) values ('CON8411','Construction Materials I',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('CON8430','Computers and You',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8258','Web Project Management',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8259','Web Programming Language II',4);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8265','Web Security Basics',4);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8267','Ecommerce',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('ENL1819T','Reporting Technical Information',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('WKT8100','Cooperation Education Work Term Preparation',5);

insert into Course (CourseCode,Title,WeeklyHours) values ('ENL8720','Technical Communication for Technicians',5);
insert into Course (CourseCode,Title,WeeklyHours) values ('GEN2007','Community Service',3);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8299','Web Project Management II',4);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8279','Introduction Computer Programming using Python',5);
insert into Course (CourseCode,Title,WeeklyHours) values ('CST8260','Database System and Concepts',5);
commit;
insert into Semester (SemesterCode, Term, Year) VALUES ('01','Winter','2021');
insert into Semester (SemesterCode, Term, Year) VALUES ('02','Spring','2022');
insert into Semester (SemesterCode, Term, Year) VALUES ('03','Fall','2022');
commit;
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CON8101','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CON8411','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CON8430','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8258','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8259','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8265','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8267','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('ENL1819T','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('WKT8100','03');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('ENL8720','02');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('GEN2007','02');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8299','02');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8279','01');
insert into CourseOffer (CourseCode, SemesterCode) VALUES ('CST8260','01');
commit;

                Select o.CourseCode,Title,WeeklyHours,o.SemesterCode from CourseOffer o
                left join Course C on o.CourseCode = C.CourseCode
                left join Semester S2 on S2.SemesterCode = o.SemesterCode;
