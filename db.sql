CREATE TABLE users (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  username  VARCHAR(40)  NOT NULL UNIQUE,
  full_name VARCHAR(80)  NOT NULL,
  email     VARCHAR(100) NOT NULL UNIQUE,
  password  VARCHAR(255) NOT NULL,
  role      ENUM('student','instructor') NOT NULL
);

CREATE TABLE courses (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  course_code   VARCHAR(20)  NOT NULL,
  course_name   VARCHAR(80)  NOT NULL,
  instructor_id INT NOT NULL,
  UNIQUE KEY uq_instructor_code (instructor_id, course_code),
  FOREIGN KEY (instructor_id) REFERENCES users(id)
);

CREATE TABLE enrollments (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id  INT NOT NULL,
  UNIQUE KEY uq_student_course (student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES users(id),
  FOREIGN KEY (course_id)  REFERENCES courses(id)
);

CREATE TABLE assignments (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  course_id   INT NOT NULL,
  title       VARCHAR(80) NOT NULL,
  description VARCHAR(255),
  due_date    DATE NOT NULL,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_due_date (due_date),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE assignment_status (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  assignment_id INT NOT NULL,
  student_id    INT NOT NULL,
  completed_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_assignment_student (assignment_id, student_id),
  FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id)    REFERENCES users(id)
);

CREATE TABLE tasks (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  user_id   INT NOT NULL,
  course_id INT NOT NULL,
  title     VARCHAR(80) NOT NULL,
  due_date  DATE NOT NULL,
  completed TINYINT(1) DEFAULT 0,
  INDEX idx_user (user_id),
  FOREIGN KEY (user_id)   REFERENCES users(id),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

