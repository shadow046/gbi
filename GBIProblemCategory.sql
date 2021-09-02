Select Task.Id, Task.TaskNumber , Task.DateCreated, Task.TaskStatus, FormField.FormId, FormField.value, Task.CreatedBy From Task
        inner join Form on Form.TaskId = Task.Id
        inner join FormField on FormField.FormId = Form.Id
        where Task.TaskNumber Like 'GBI%'
        and FormField.FieldId LIKE 'GBIProblemCategory'
        AND FormField.Value is NOT null
        And Formfield.Value != ''
        and Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') >= '09-02-2021';
