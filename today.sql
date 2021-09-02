Select TaskNumber From Task
        WHERE TaskNumber Like 'GBI%'
        AND Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') >= '09-02-2021';
