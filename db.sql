CREATE TABLE hotels
(
    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL,
    address     VARCHAR(255) NOT NULL,
    city        VARCHAR(255) NOT NULL,
    state       VARCHAR(255) DEFAULT NULL,
    country     VARCHAR(255) DEFAULT NULL,
    email       VARCHAR(255),
    star_rating TINYINT,
    created     DATETIME     DEFAULT NULL,
    modified    DATETIME     DEFAULT NULL
);

CREATE TABLE room_types
(
    id            INT PRIMARY KEY AUTO_INCREMENT,
    type_name     VARCHAR(255)   NOT NULL,
    description   TEXT,
    max_occupancy INT            NOT NULL,
    base_price    DECIMAL(10, 2) NOT NULL,
    pets_allowed  TINYINT(1) DEFAULT 0,
    created       DATETIME DEFAULT NULL,
    modified      DATETIME DEFAULT NULL
);

CREATE TABLE rooms
(
    id           INT PRIMARY KEY AUTO_INCREMENT,
    hotel_id     INT         NOT NULL,
    room_type_id INT         NOT NULL,
    room_number  VARCHAR(10) NOT NULL,
    is_available TINYINT(1) DEFAULT 1,
    created      DATETIME DEFAULT NULL,
    modified     DATETIME DEFAULT NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE,
    FOREIGN KEY (room_type_id) REFERENCES room_types (id)
);

CREATE TABLE customers
(
    id            INT PRIMARY KEY AUTO_INCREMENT,
    first_name    VARCHAR(255) DEFAULT NULL,
    last_name     VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    address       VARCHAR(255),
    date_of_birth DATE,
    created       DATETIME     DEFAULT NULL,
    modified      DATETIME     DEFAULT NULL
);

CREATE TABLE bookings
(
    id               INT PRIMARY KEY AUTO_INCREMENT,
    customer_id      INT            NOT NULL,
    room_id          INT            NOT NULL,
    check_in_date    DATE           NOT NULL,
    check_out_date   DATE           NOT NULL,
    number_of_guests INT            NOT NULL,
    total_amount     DECIMAL(10, 2) NOT NULL,
    booking_status   VARCHAR(255) DEFAULT 'confirmed',
    special_requests TEXT,
    created          DATETIME     DEFAULT NULL,
    modified         DATETIME     DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers (id),
    FOREIGN KEY (room_id) REFERENCES rooms (id)
);

CREATE TABLE payments
(
    id             INT PRIMARY KEY AUTO_INCREMENT,
    booking_id     INT            NOT NULL,
    amount         DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(255)   NOT NULL,
    payment_status VARCHAR(255) DEFAULT 'pending',
    transaction_id VARCHAR(255),
    created        DATETIME     DEFAULT NULL,
    modified       DATETIME     DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings (id)
);

-- HOTELS
INSERT INTO hotels (name, address, city, state, country, email, star_rating, created, modified)
VALUES ('Grand Plaza', '123 Main St', 'New York', 'NY', 'USA', 'info@example.com', 5, NOW(), NOW()),
       ('Oceanview Resort', '456 Beach Rd', 'Miami', 'FL', 'USA', 'contact@example.com', 4, NOW(), NOW()),
       ('Mountain Lodge', '789 Hilltop Ave', 'Denver', 'CO', 'USA', 'stay@example.com', 3, NOW(), NOW());

-- ROOM TYPES
INSERT INTO room_types (type_name, description, max_occupancy, base_price, pets_allowed, created, modified)
VALUES ('Single', 'One single bed, perfect for solo travelers.', 1, 90.00, 0, NOW(), NOW()),
       ('Double', 'Double bed for two guests.', 2, 150.00, 0, NOW(), NOW()),
       ('Suite', 'Spacious suite with living area.', 4, 300.00, 1, NOW(), NOW());

-- ROOMS
INSERT INTO rooms (hotel_id, room_type_id, room_number, is_available, created, modified)
VALUES (1, 1, '101', 1, NOW(), NOW()),
       (1, 2, '102', 1, NOW(), NOW()),
       (1, 3, '201', 0, NOW(), NOW()),
       (2, 2, '301', 1, NOW(), NOW()),
       (2, 3, '302', 1, NOW(), NOW()),
       (3, 1, '401', 0, NOW(), NOW());

-- CUSTOMERS
INSERT INTO customers (first_name, last_name, email, address, date_of_birth, created, modified)
VALUES ('Alice', 'Johnson', 'alice@example.com', '12 Oak St, New York, NY', '1985-04-12', NOW(), NOW()),
       ('Bob', 'Smith', 'bob@example.com', '89 Pine Ave, Miami, FL', '1990-08-25', NOW(), NOW()),
       ('Charlie', 'Brown', 'charlie@example.com', '55 Lake Rd, Denver, CO', '1978-11-02', NOW(), NOW());

-- BOOKINGS
INSERT INTO bookings (customer_id, room_id, check_in_date, check_out_date, number_of_guests, total_amount,
                      booking_status, special_requests, created, modified)
VALUES (1, 1, '2025-10-10', '2025-10-12', 1, 180.00, 'confirmed', NULL, NOW(), NOW()),
       (2, 2, '2025-10-15', '2025-10-18', 2, 450.00, 'pending', 'Near elevator', NOW(), NOW()),
       (3, 5, '2025-11-01', '2025-11-04', 3, 900.00, 'confirmed', NULL, NOW(), NOW()),
       (1, 4, '2025-09-28', '2025-09-30', 2, 300.00, 'completed', NULL, NOW(), NOW()),
       (2, 6, '2025-08-20', '2025-08-22', 1, 180.00, 'cancelled', 'No smoking room', NOW(), NOW()),
       (3, 1, '2025-10-20', '2025-10-23', 1, 270.00, 'confirmed', NULL, NOW(), NOW()),
       (1, 2, '2025-11-05', '2025-11-09', 2, 600.00, 'pending', NULL, NOW(), NOW()),
       (2, 3, '2025-12-01', '2025-12-07', 2, 1800.00, 'confirmed', 'Sea view if possible', NOW(), NOW()),
       (3, 4, '2025-12-10', '2025-12-13', 2, 450.00, 'confirmed', NULL, NOW(), NOW()),
       (1, 5, '2025-12-20', '2025-12-24', 4, 1200.00, 'confirmed', 'Extra pillows', NOW(), NOW()),
       (2, 6, '2026-01-02', '2026-01-05', 1, 270.00, 'confirmed', NULL, NOW(), NOW()),
       (3, 1, '2026-01-10', '2026-01-15', 1, 450.00, 'confirmed', 'Late check-out', NOW(), NOW()),
       (1, 2, '2026-01-20', '2026-01-22', 2, 300.00, 'cancelled', NULL, NOW(), NOW()),
       (2, 3, '2026-02-01', '2026-02-05', 2, 1200.00, 'confirmed', 'Quiet room', NOW(), NOW()),
       (3, 4, '2026-02-10', '2026-02-14', 3, 600.00, 'pending', NULL, NOW(), NOW()),
       (1, 5, '2026-02-20', '2026-02-25', 4, 1500.00, 'confirmed', 'Baby crib needed', NOW(), NOW()),
       (2, 6, '2026-03-01', '2026-03-04', 1, 270.00, 'completed', NULL, NOW(), NOW()),
       (3, 1, '2026-03-10', '2026-03-15', 1, 450.00, 'confirmed', NULL, NOW(), NOW()),
       (1, 2, '2026-03-20', '2026-03-24', 2, 600.00, 'confirmed', 'Early check-in', NOW(), NOW()),
       (2, 3, '2026-04-01', '2026-04-05', 3, 1500.00, 'confirmed', NULL, NOW(), NOW());

-- PAYMENTS
INSERT INTO payments (booking_id, amount, payment_method, payment_status, transaction_id, created, modified)
VALUES (1, 1200.00, 'credit_card', 'completed', 'TXN1001', NOW(), NOW()),
       (2, 600.00, 'paypal', 'completed', 'TXN1002', NOW(), NOW()),
       (3, 180.00, 'credit_card', 'completed', 'TXN1003', NOW(), NOW());
