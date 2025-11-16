-- Create attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    attendance_date DATE NOT NULL,
    class VARCHAR(10) NOT NULL,
    stream VARCHAR(10) NULL,
    present_count INTEGER NOT NULL,
    absent_count INTEGER NOT NULL,
    total_students INTEGER NOT NULL,
    term VARCHAR(20) NOT NULL,
    academic_year VARCHAR(10) NOT NULL,
    remarks TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Insert sample data
INSERT INTO attendance (attendance_date, class, stream, present_count, absent_count, total_students, term, academic_year, remarks, created_at, updated_at)
VALUES 
('2025-01-01', 'S.1', 'A', 45, 5, 50, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-02', 'S.1', 'A', 43, 7, 50, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-01', 'S.1', 'B', 38, 2, 40, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-02', 'S.1', 'B', 37, 3, 40, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-01', 'S.2', 'A', 42, 3, 45, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-02', 'S.2', 'A', 41, 4, 45, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-01', 'S.2', 'B', 39, 1, 40, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-02', 'S.2', 'B', 38, 2, 40, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-01', 'S.3', 'A', 44, 1, 45, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('2025-01-02', 'S.3', 'A', 43, 2, 45, 'Term 1', '2025', 'Regular school day', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);