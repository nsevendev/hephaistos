# Flow messenger  

## Requirements for command. 

- DTO for this action (Create, update etc ...), he has the VO typing for properties   
- Create a exception Invalid Argument for this object  
- Create a command with argument the DTO for this action   
- Create a handler for this command. 

## Flow  

1. deserialisation of request and create DTO for this action (in function `deserializeAndValidate` in abstract controller).  
2. validation of DTO for this action with validator SF (in function `deserializeAndValidate` in abstract controller).  
3. dispatch your command with DTO for this action and send in worker for rabbit.  
4. return response api  
