# Laravel Tech Task Demo API

This project is my solution for the **"Laravel Tech Task Demo API"**. Its primary purpose is to list, add, edit, and delete tasks.

- **Task Model Structure**:
  - **Name**: Minimum 3, maximum 100 characters.
  - **Description**: Minimum 10, maximum 5000 characters.
- **Endpoints**:
  - `GET /api/tasks`: View all tasks (no user restrictions).
  - `POST /api/tasks`: Create a new task.
  - `PUT /api/tasks/{task_id}?token={xxx}`: Update an existing task (secured via token).
  - `DELETE /api/tasks/{task_id}?token={xxx}`: Delete a task (secured via token).
- **Security**: Edit and delete operations are secured using a secure token.
- **Database**: Utilizes an sqlite database
- **Additional Features**:
  - **Request Logging**: All requests are logged via middleware into a log file.
  - **Soft Deleting**: Implemented to allow recovery of deleted tasks.

## Testing

To run the unit tests:

```bash
php artisan test
```