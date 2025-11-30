Mzuzu University ICT Weekend Timetabling System (Genetic Algorithm)

A web-based timetabling system designed for the Mzuzu University ICT Weekend Programme, developed as a first-year group project.
The system uses a Genetic Algorithm (GA) to automatically generate optimized, conflict-free class timetables based on course, lecturer, room, and student-group data.

🚀 Overview

Manual timetable creation is time-consuming and error-prone. This system automates the process by applying Genetic Algorithm optimization, simulating natural selection to evolve the best possible timetable.

The web interface allows admins to manage data inputs and generate timetables with a single click. The system evaluates thousands of combinations and returns a timetable that satisfies constraints such as:

No lecturer double-booking

No room conflicts

Matching room capacity with student group size

No overlapping classes for student groups

Weekend-specific time slots

🧬 How the Genetic Algorithm Works

The GA improves timetable quality through:

Initialization – Create a random population of timetable solutions

Fitness Evaluation – Measure constraint satisfaction for each solution

Selection – Pick the best-performing timetables

Crossover – Combine parents to create new offspring

Mutation – Randomly alter schedules to introduce variation

Evolution Loop – Repeat until a near-optimal timetable is produced

This approach ensures a balanced, efficient schedule with minimal conflicts.

📌 Key Features

Web-based interface (browser accessible)

Admin panel for managing:

Courses

Lecturers

Rooms

Time slots

Student groups

Automatic timetable generation using GA

Conflict detection & resolution

View timetable by:

Lecturer

Room

Student group

Export options (if implemented)

Regenerate timetable anytime
