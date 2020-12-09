# Architecture:
An example of how to implement the Clean Architecture ideas in PHP.

Decided not to use a framework as I dont want all this overhead to choke and hide the spirit of the system.
Instead, a very lightweight web and console I/O mechanism was made, as well as a very lightweight cache and logger (filesystem).
This also shows how the core of the system is unaffected by these particularities as it works with I/O agnostic data to protect
it from I/O details, and Interfaces to protect it from low-level implementation details.

# Vocabulary:
- Business Case: A high level description of something the system should be doing.
    Ideally it should be almost able to be read by a non-programmer.
    It has no dependencies to anything concrete, but only to Interfaces. It is doubly isolated from details, on one hand from the I/O mechanism 
    (Adapters do that), and on the other hand from the specific way that lower-level operations are done, 
    (Services do that). Abbreviated to Case from now on.
    
- Entry point: Any entry point to the system (a main function), for example an http server, or a cli.
   
- Adapter: A function that sits between an entry point and the Case. It gets whatever input the nature of that
    entry point can provide and adapts it to a Case's input. It then takes the Case's output and adapts it
    to the output that the entry point allows.
    
- Service: A class that provides an API to do some low-level operations. It is never referenced directly inside a Case,
    but only through an Interface that the Service satisfies. Examples of Services are Cache, Logger, Weatherman.

```
project/              
├── src/                  Our code.
│   ├── core              The Core of the system. Immune to I/0 & infrastructure changes.
│   │   ├── cases/        Business cases. High level. Can only do stuff because it is injected a Di with services.
│   │   └── models/       Domain Models.
│   └── services/         Services to be injected to Business Cases for doing low-level stuff.
│   └── entry_points/     Entry points for the system.
│       ├── cli/            
│       │   ├── adapters/   
│       │   └── main.go     
│       └── web
|           ├── adapters/          
│           └── main.go    
└── var/                  logs, cache, etch files.
```
