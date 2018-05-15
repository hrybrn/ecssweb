CREATE TABLE refund_requests (
username VARCHAR(255) NOT NULL,
num_tickets INTEGER NOT NULL,
comments TEXT,
request_time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
PRIMARY KEY (username)
);
