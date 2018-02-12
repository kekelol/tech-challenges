To launch project, please go to "fullstack/senior/backend", and type:
```bash
php -S localhost:8080 -t web web/index.php
#Go to http://localhost:8080/XX1, it will show JSON results
```

The backend will loop threw each JSON data file (surveys) that are in the data folder, and
depending on the parameter (http://localhost:8080/XX1, http://localhost:8080/XX2, http://localhost:8080/XX3 ...),
the backend will collect data from all JSON files which correspond to that agency code (XX1, XX2, XX3 ...).

It then aggregates results like so:
* For QCM questions, it sums every answer for every possible answer and returns an array of that (For instance here, the total number available in the agency for each best seller product).
* For numeric questions, it averages every answer (For instance here, it returns the average number of products from every surveys that the agency has completed. But also the sum, and the number).

It then constructs and returns a JSON of aggregated answers.

This backend part works with 2 questions (as it is right now in the data files), but would also work with 3, 4, 5 questions per surveys, even thought it would need some more testing.
A few adjustements have to be made to implement more question types (other than qcm and numeric), but it has been designed to allow these adjustements, notably with "switch" usage.

To improve this, next steps would be to :
* Test with many more files to check for performance issues.
* Test with more than two questions per survey (maybe 4, 5 ..)
* Refactor code and maybe rethink design (classes folders).
