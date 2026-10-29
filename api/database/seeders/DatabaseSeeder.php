<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::transaction(function (): void {
            foreach ($this->questions() as $item) {
                $question = Question::create([
                    'question' => $item['question']
                ]);

                foreach ($item['answers'] as $answer) {
                    Answer::create([
                        'question_id' => $question->id,
                        'text' => $answer['text'],
                        'is_correct' => $answer['is_correct'],
                    ]);
                }
            }
        });
    }

    public function questions(): array
    {
        return [
            [
                'id' => 1,
                'question' => 'What does PHP stand for?',
                'answers' => [
                    ['id' => 1, 'text' => 'Private Hosting Platform', 'is_correct' => false],
                    ['id' => 2, 'text' => 'Personal Home Page', 'is_correct' => false],
                    ['id' => 3, 'text' => 'PHP: Hypertext Preprocessor', 'is_correct' => true],
                ],
            ],
            [
                'id' => 2,
                'question' => 'Which symbol prefixes a PHP variable?',
                'answers' => [
                    ['id' => 4, 'text' => '$', 'is_correct' => true],
                    ['id' => 5, 'text' => '#', 'is_correct' => false],
                    ['id' => 6, 'text' => '@', 'is_correct' => false],
                ],
            ],
            [
                'id' => 3,
                'question' => 'Which function returns the byte length of a string?',
                'answers' => [
                    ['id' => 7, 'text' => 'sizeof()', 'is_correct' => false],
                    ['id' => 8, 'text' => 'count()', 'is_correct' => false],
                    ['id' => 9, 'text' => 'strlen()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 4,
                'question' => 'Which tag opens a PHP code block?',
                'answers' => [
                    ['id' => 10, 'text' => '<php>', 'is_correct' => false],
                    ['id' => 11, 'text' => '<?php', 'is_correct' => true],
                    ['id' => 12, 'text' => '<script php>', 'is_correct' => false],
                ],
            ],
            [
                'id' => 5,
                'question' => 'Which operator concatenates strings?',
                'answers' => [
                    ['id' => 13, 'text' => '.', 'is_correct' => true],
                    ['id' => 14, 'text' => '&', 'is_correct' => false],
                    ['id' => 15, 'text' => '+', 'is_correct' => false],
                ],
            ],
            [
                'id' => 6,
                'question' => 'Which operator checks both value and type equality?',
                'answers' => [
                    ['id' => 16, 'text' => '==', 'is_correct' => false],
                    ['id' => 17, 'text' => '===', 'is_correct' => true],
                    ['id' => 18, 'text' => '=', 'is_correct' => false],
                ],
            ],
            [
                'id' => 7,
                'question' => 'Which keyword defines a function?',
                'answers' => [
                    ['id' => 19, 'text' => 'function', 'is_correct' => true],
                    ['id' => 20, 'text' => 'def', 'is_correct' => false],
                    ['id' => 21, 'text' => 'func', 'is_correct' => false],
                ],
            ],
            [
                'id' => 8,
                'question' => 'Which function counts array elements?',
                'answers' => [
                    ['id' => 22, 'text' => 'length()', 'is_correct' => false],
                    ['id' => 23, 'text' => 'count()', 'is_correct' => true],
                    ['id' => 24, 'text' => 'strlen()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 9,
                'question' => 'Which superglobal contains query-string parameters?',
                'answers' => [
                    ['id' => 25, 'text' => '$_POST', 'is_correct' => false],
                    ['id' => 26, 'text' => '$_COOKIE', 'is_correct' => false],
                    ['id' => 27, 'text' => '$_GET', 'is_correct' => true],
                ],
            ],
            [
                'id' => 10,
                'question' => 'Which superglobal contains submitted POST fields?',
                'answers' => [
                    ['id' => 28, 'text' => '$_GET', 'is_correct' => false],
                    ['id' => 29, 'text' => '$_ENV', 'is_correct' => false],
                    ['id' => 30, 'text' => '$_POST', 'is_correct' => true],
                ],
            ],
            [
                'id' => 11,
                'question' => 'Which function checks whether a variable is set and not null?',
                'answers' => [
                    ['id' => 31, 'text' => 'defined()', 'is_correct' => false],
                    ['id' => 32, 'text' => 'empty()', 'is_correct' => false],
                    ['id' => 33, 'text' => 'isset()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 12,
                'question' => 'Which function checks whether a value is an array?',
                'answers' => [
                    ['id' => 34, 'text' => 'array_check()', 'is_correct' => false],
                    ['id' => 35, 'text' => 'is_list()', 'is_correct' => false],
                    ['id' => 36, 'text' => 'is_array()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 13,
                'question' => 'Which function removes whitespace from both ends of a string?',
                'answers' => [
                    ['id' => 37, 'text' => 'strip()', 'is_correct' => false],
                    ['id' => 38, 'text' => 'clean()', 'is_correct' => false],
                    ['id' => 39, 'text' => 'trim()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 14,
                'question' => 'Which function splits a string using a delimiter?',
                'answers' => [
                    ['id' => 40, 'text' => 'join()', 'is_correct' => false],
                    ['id' => 41, 'text' => 'explode()', 'is_correct' => true],
                    ['id' => 42, 'text' => 'implode()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 15,
                'question' => 'Which function joins array elements into a string?',
                'answers' => [
                    ['id' => 43, 'text' => 'split()', 'is_correct' => false],
                    ['id' => 44, 'text' => 'explode()', 'is_correct' => false],
                    ['id' => 45, 'text' => 'implode()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 16,
                'question' => 'Which construct outputs one or more strings?',
                'answers' => [
                    ['id' => 46, 'text' => 'yield', 'is_correct' => false],
                    ['id' => 47, 'text' => 'echo', 'is_correct' => true],
                    ['id' => 48, 'text' => 'return', 'is_correct' => false],
                ],
            ],
            [
                'id' => 17,
                'question' => 'Which keyword exits a function and optionally returns a value?',
                'answers' => [
                    ['id' => 49, 'text' => 'continue', 'is_correct' => false],
                    ['id' => 50, 'text' => 'break', 'is_correct' => false],
                    ['id' => 51, 'text' => 'return', 'is_correct' => true],
                ],
            ],
            [
                'id' => 18,
                'question' => 'Which loop always executes its body at least once?',
                'answers' => [
                    ['id' => 52, 'text' => 'do...while', 'is_correct' => true],
                    ['id' => 53, 'text' => 'foreach', 'is_correct' => false],
                    ['id' => 54, 'text' => 'while', 'is_correct' => false],
                ],
            ],
            [
                'id' => 19,
                'question' => 'Which loop is designed to iterate over arrays and Traversable objects?',
                'answers' => [
                    ['id' => 55, 'text' => 'switch', 'is_correct' => false],
                    ['id' => 56, 'text' => 'if', 'is_correct' => false],
                    ['id' => 57, 'text' => 'foreach', 'is_correct' => true],
                ],
            ],
            [
                'id' => 20,
                'question' => 'Which statement selects among multiple branches using case labels?',
                'answers' => [
                    ['id' => 58, 'text' => 'switch', 'is_correct' => true],
                    ['id' => 59, 'text' => 'foreach', 'is_correct' => false],
                    ['id' => 60, 'text' => 'try', 'is_correct' => false],
                ],
            ],
            [
                'id' => 21,
                'question' => 'Which keyword skips to the next loop iteration?',
                'answers' => [
                    ['id' => 61, 'text' => 'continue', 'is_correct' => true],
                    ['id' => 62, 'text' => 'return', 'is_correct' => false],
                    ['id' => 63, 'text' => 'break', 'is_correct' => false],
                ],
            ],
            [
                'id' => 22,
                'question' => 'Which keyword ends the current loop?',
                'answers' => [
                    ['id' => 64, 'text' => 'yield', 'is_correct' => false],
                    ['id' => 65, 'text' => 'break', 'is_correct' => true],
                    ['id' => 66, 'text' => 'continue', 'is_correct' => false],
                ],
            ],
            [
                'id' => 23,
                'question' => 'Which operator provides a fallback only when the left operand is null or undefined?',
                'answers' => [
                    ['id' => 67, 'text' => '??', 'is_correct' => true],
                    ['id' => 68, 'text' => '&&', 'is_correct' => false],
                    ['id' => 69, 'text' => '?:', 'is_correct' => false],
                ],
            ],
            [
                'id' => 24,
                'question' => 'Which operator accesses an object property or method?',
                'answers' => [
                    ['id' => 70, 'text' => '::', 'is_correct' => false],
                    ['id' => 71, 'text' => '=>', 'is_correct' => false],
                    ['id' => 72, 'text' => '->', 'is_correct' => true],
                ],
            ],
            [
                'id' => 25,
                'question' => 'Which operator accesses a static class member?',
                'answers' => [
                    ['id' => 73, 'text' => '->', 'is_correct' => false],
                    ['id' => 74, 'text' => '=>', 'is_correct' => false],
                    ['id' => 75, 'text' => '::', 'is_correct' => true],
                ],
            ],
            [
                'id' => 26,
                'question' => 'Which keyword creates an object instance?',
                'answers' => [
                    ['id' => 76, 'text' => 'new', 'is_correct' => true],
                    ['id' => 77, 'text' => 'make', 'is_correct' => false],
                    ['id' => 78, 'text' => 'instance', 'is_correct' => false],
                ],
            ],
            [
                'id' => 27,
                'question' => 'Which keyword declares a class?',
                'answers' => [
                    ['id' => 79, 'text' => 'object', 'is_correct' => false],
                    ['id' => 80, 'text' => 'struct', 'is_correct' => false],
                    ['id' => 81, 'text' => 'class', 'is_correct' => true],
                ],
            ],
            [
                'id' => 28,
                'question' => 'Which visibility allows access only within the declaring class?',
                'answers' => [
                    ['id' => 82, 'text' => 'private', 'is_correct' => true],
                    ['id' => 83, 'text' => 'protected', 'is_correct' => false],
                    ['id' => 84, 'text' => 'public', 'is_correct' => false],
                ],
            ],
            [
                'id' => 29,
                'question' => 'Which visibility permits access from a class and its subclasses?',
                'answers' => [
                    ['id' => 85, 'text' => 'protected', 'is_correct' => true],
                    ['id' => 86, 'text' => 'public', 'is_correct' => false],
                    ['id' => 87, 'text' => 'private', 'is_correct' => false],
                ],
            ],
            [
                'id' => 30,
                'question' => 'Which visibility permits access from anywhere?',
                'answers' => [
                    ['id' => 88, 'text' => 'public', 'is_correct' => true],
                    ['id' => 89, 'text' => 'private', 'is_correct' => false],
                    ['id' => 90, 'text' => 'protected', 'is_correct' => false],
                ],
            ],
            [
                'id' => 31,
                'question' => 'Which method is called when an object is constructed?',
                'answers' => [
                    ['id' => 91, 'text' => '__create()', 'is_correct' => false],
                    ['id' => 92, 'text' => '__construct()', 'is_correct' => true],
                    ['id' => 93, 'text' => '__init()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 32,
                'question' => 'Which keyword indicates class inheritance?',
                'answers' => [
                    ['id' => 94, 'text' => 'inherits', 'is_correct' => false],
                    ['id' => 95, 'text' => 'extends', 'is_correct' => true],
                    ['id' => 96, 'text' => 'implements', 'is_correct' => false],
                ],
            ],
            [
                'id' => 33,
                'question' => 'Which keyword indicates that a class implements an interface?',
                'answers' => [
                    ['id' => 97, 'text' => 'implements', 'is_correct' => true],
                    ['id' => 98, 'text' => 'uses', 'is_correct' => false],
                    ['id' => 99, 'text' => 'extends', 'is_correct' => false],
                ],
            ],
            [
                'id' => 34,
                'question' => 'Which keyword declares an interface?',
                'answers' => [
                    ['id' => 100, 'text' => 'contract', 'is_correct' => false],
                    ['id' => 101, 'text' => 'interface', 'is_correct' => true],
                    ['id' => 102, 'text' => 'protocol', 'is_correct' => false],
                ],
            ],
            [
                'id' => 35,
                'question' => 'Which keyword imports a trait into a class?',
                'answers' => [
                    ['id' => 103, 'text' => 'use', 'is_correct' => true],
                    ['id' => 104, 'text' => 'include', 'is_correct' => false],
                    ['id' => 105, 'text' => 'import', 'is_correct' => false],
                ],
            ],
            [
                'id' => 36,
                'question' => 'Which keyword prevents a class from being extended?',
                'answers' => [
                    ['id' => 106, 'text' => 'static', 'is_correct' => false],
                    ['id' => 107, 'text' => 'final', 'is_correct' => true],
                    ['id' => 108, 'text' => 'sealed', 'is_correct' => false],
                ],
            ],
            [
                'id' => 37,
                'question' => 'Which keyword declares an abstract class?',
                'answers' => [
                    ['id' => 109, 'text' => 'interface', 'is_correct' => false],
                    ['id' => 110, 'text' => 'virtual', 'is_correct' => false],
                    ['id' => 111, 'text' => 'abstract', 'is_correct' => true],
                ],
            ],
            [
                'id' => 38,
                'question' => 'Can an abstract class be instantiated directly?',
                'answers' => [
                    ['id' => 112, 'text' => 'No', 'is_correct' => true],
                    ['id' => 113, 'text' => 'Yes', 'is_correct' => false],
                    ['id' => 114, 'text' => 'Only with new static', 'is_correct' => false],
                ],
            ],
            [
                'id' => 39,
                'question' => 'What does $this refer to inside an instance method?',
                'answers' => [
                    ['id' => 115, 'text' => 'The current object', 'is_correct' => true],
                    ['id' => 116, 'text' => 'The global scope', 'is_correct' => false],
                    ['id' => 117, 'text' => 'The parent class', 'is_correct' => false],
                ],
            ],
            [
                'id' => 40,
                'question' => 'Which keyword refers to the parent class?',
                'answers' => [
                    ['id' => 118, 'text' => 'parent', 'is_correct' => true],
                    ['id' => 119, 'text' => 'base', 'is_correct' => false],
                    ['id' => 120, 'text' => 'super', 'is_correct' => false],
                ],
            ],
            [
                'id' => 41,
                'question' => 'Which keyword refers to the current class in static context?',
                'answers' => [
                    ['id' => 121, 'text' => 'this', 'is_correct' => false],
                    ['id' => 122, 'text' => 'self', 'is_correct' => true],
                    ['id' => 123, 'text' => 'parent', 'is_correct' => false],
                ],
            ],
            [
                'id' => 42,
                'question' => 'Which mechanism resolves a class according to the called class at runtime?',
                'answers' => [
                    ['id' => 124, 'text' => 'Late static binding', 'is_correct' => true],
                    ['id' => 125, 'text' => 'Variable variables', 'is_correct' => false],
                    ['id' => 126, 'text' => 'Method overloading', 'is_correct' => false],
                ],
            ],
            [
                'id' => 43,
                'question' => 'Which keyword enables late static binding in static member references?',
                'answers' => [
                    ['id' => 127, 'text' => 'parent', 'is_correct' => false],
                    ['id' => 128, 'text' => 'static', 'is_correct' => true],
                    ['id' => 129, 'text' => 'self', 'is_correct' => false],
                ],
            ],
            [
                'id' => 44,
                'question' => 'Which magic method handles reading inaccessible or undefined object properties?',
                'answers' => [
                    ['id' => 130, 'text' => '__get()', 'is_correct' => true],
                    ['id' => 131, 'text' => '__fetch()', 'is_correct' => false],
                    ['id' => 132, 'text' => '__read()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 45,
                'question' => 'Which magic method handles calls to inaccessible or undefined instance methods?',
                'answers' => [
                    ['id' => 133, 'text' => '__call()', 'is_correct' => true],
                    ['id' => 134, 'text' => '__get()', 'is_correct' => false],
                    ['id' => 135, 'text' => '__invoke()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 46,
                'question' => 'Which magic method is invoked when an object is used as a function?',
                'answers' => [
                    ['id' => 136, 'text' => '__callStatic()', 'is_correct' => false],
                    ['id' => 137, 'text' => '__invoke()', 'is_correct' => true],
                    ['id' => 138, 'text' => '__toString()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 47,
                'question' => 'Which magic method provides a string representation of an object?',
                'answers' => [
                    ['id' => 139, 'text' => '__serialize()', 'is_correct' => false],
                    ['id' => 140, 'text' => '__toString()', 'is_correct' => true],
                    ['id' => 141, 'text' => '__string()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 48,
                'question' => 'Which keyword declares a namespace?',
                'answers' => [
                    ['id' => 142, 'text' => 'package', 'is_correct' => false],
                    ['id' => 143, 'text' => 'module', 'is_correct' => false],
                    ['id' => 144, 'text' => 'namespace', 'is_correct' => true],
                ],
            ],
            [
                'id' => 49,
                'question' => 'Which keyword imports a namespaced class alias?',
                'answers' => [
                    ['id' => 145, 'text' => 'require', 'is_correct' => false],
                    ['id' => 146, 'text' => 'use', 'is_correct' => true],
                    ['id' => 147, 'text' => 'namespace', 'is_correct' => false],
                ],
            ],
            [
                'id' => 50,
                'question' => 'Which operator is used in associative array key-value pairs?',
                'answers' => [
                    ['id' => 148, 'text' => '::', 'is_correct' => false],
                    ['id' => 149, 'text' => '->', 'is_correct' => false],
                    ['id' => 150, 'text' => '=>', 'is_correct' => true],
                ],
            ],
            [
                'id' => 51,
                'question' => 'Which syntax creates an empty array?',
                'answers' => [
                    ['id' => 151, 'text' => '()', 'is_correct' => false],
                    ['id' => 152, 'text' => '{}', 'is_correct' => false],
                    ['id' => 153, 'text' => '[]', 'is_correct' => true],
                ],
            ],
            [
                'id' => 52,
                'question' => 'Which function appends one or more elements to an array?',
                'answers' => [
                    ['id' => 154, 'text' => 'array_shift()', 'is_correct' => false],
                    ['id' => 155, 'text' => 'array_push()', 'is_correct' => true],
                    ['id' => 156, 'text' => 'array_pop()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 53,
                'question' => 'Which function removes the last array element?',
                'answers' => [
                    ['id' => 157, 'text' => 'array_slice()', 'is_correct' => false],
                    ['id' => 158, 'text' => 'array_pop()', 'is_correct' => true],
                    ['id' => 159, 'text' => 'array_shift()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 54,
                'question' => 'Which function removes the first array element?',
                'answers' => [
                    ['id' => 160, 'text' => 'array_unshift()', 'is_correct' => false],
                    ['id' => 161, 'text' => 'array_pop()', 'is_correct' => false],
                    ['id' => 162, 'text' => 'array_shift()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 55,
                'question' => 'Which function merges arrays into one array?',
                'answers' => [
                    ['id' => 163, 'text' => 'array_keys()', 'is_correct' => false],
                    ['id' => 164, 'text' => 'array_filter()', 'is_correct' => false],
                    ['id' => 165, 'text' => 'array_merge()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 56,
                'question' => 'Which function transforms every element using a callback?',
                'answers' => [
                    ['id' => 166, 'text' => 'array_map()', 'is_correct' => true],
                    ['id' => 167, 'text' => 'array_reduce()', 'is_correct' => false],
                    ['id' => 168, 'text' => 'array_filter()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 57,
                'question' => 'Which function keeps array elements that pass a callback test?',
                'answers' => [
                    ['id' => 169, 'text' => 'array_map()', 'is_correct' => false],
                    ['id' => 170, 'text' => 'array_values()', 'is_correct' => false],
                    ['id' => 171, 'text' => 'array_filter()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 58,
                'question' => 'Which function reduces an array to a single value using a callback?',
                'answers' => [
                    ['id' => 172, 'text' => 'array_merge()', 'is_correct' => false],
                    ['id' => 173, 'text' => 'array_map()', 'is_correct' => false],
                    ['id' => 174, 'text' => 'array_reduce()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 59,
                'question' => 'Which function checks whether an array contains a given value?',
                'answers' => [
                    ['id' => 175, 'text' => 'array_key_exists()', 'is_correct' => false],
                    ['id' => 176, 'text' => 'in_array()', 'is_correct' => true],
                    ['id' => 177, 'text' => 'isset()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 60,
                'question' => 'Which function checks whether a specified key exists in an array?',
                'answers' => [
                    ['id' => 178, 'text' => 'array_search()', 'is_correct' => false],
                    ['id' => 179, 'text' => 'array_key_exists()', 'is_correct' => true],
                    ['id' => 180, 'text' => 'in_array()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 61,
                'question' => 'Which function returns all keys from an array?',
                'answers' => [
                    ['id' => 181, 'text' => 'array_values()', 'is_correct' => false],
                    ['id' => 182, 'text' => 'array_flip()', 'is_correct' => false],
                    ['id' => 183, 'text' => 'array_keys()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 62,
                'question' => 'Which function sorts an array by value while preserving its keys?',
                'answers' => [
                    ['id' => 184, 'text' => 'ksort()', 'is_correct' => false],
                    ['id' => 185, 'text' => 'sort()', 'is_correct' => false],
                    ['id' => 186, 'text' => 'asort()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 63,
                'question' => 'Which function sorts an array by key in ascending order?',
                'answers' => [
                    ['id' => 187, 'text' => 'asort()', 'is_correct' => false],
                    ['id' => 188, 'text' => 'ksort()', 'is_correct' => true],
                    ['id' => 189, 'text' => 'rsort()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 64,
                'question' => 'Which function returns a JSON string from a PHP value?',
                'answers' => [
                    ['id' => 190, 'text' => 'json_encode()', 'is_correct' => true],
                    ['id' => 191, 'text' => 'json_decode()', 'is_correct' => false],
                    ['id' => 192, 'text' => 'serialize_json()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 65,
                'question' => 'Which function converts a JSON string into a PHP value?',
                'answers' => [
                    ['id' => 193, 'text' => 'json_decode()', 'is_correct' => true],
                    ['id' => 194, 'text' => 'json_encode()', 'is_correct' => false],
                    ['id' => 195, 'text' => 'parse_json()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 66,
                'question' => 'Which JSON flag makes json_encode format output with indentation?',
                'answers' => [
                    ['id' => 196, 'text' => 'JSON_FORMATTED', 'is_correct' => false],
                    ['id' => 197, 'text' => 'JSON_PRETTY_PRINT', 'is_correct' => true],
                    ['id' => 198, 'text' => 'JSON_INDENT', 'is_correct' => false],
                ],
            ],
            [
                'id' => 67,
                'question' => 'Which keyword starts exception handling?',
                'answers' => [
                    ['id' => 199, 'text' => 'catch', 'is_correct' => false],
                    ['id' => 200, 'text' => 'try', 'is_correct' => true],
                    ['id' => 201, 'text' => 'throw', 'is_correct' => false],
                ],
            ],
            [
                'id' => 68,
                'question' => 'Which keyword throws an exception or error object?',
                'answers' => [
                    ['id' => 202, 'text' => 'except', 'is_correct' => false],
                    ['id' => 203, 'text' => 'raise', 'is_correct' => false],
                    ['id' => 204, 'text' => 'throw', 'is_correct' => true],
                ],
            ],
            [
                'id' => 69,
                'question' => 'Which block runs after try/catch regardless of whether an exception was thrown?',
                'answers' => [
                    ['id' => 205, 'text' => 'finally', 'is_correct' => true],
                    ['id' => 206, 'text' => 'default', 'is_correct' => false],
                    ['id' => 207, 'text' => 'always', 'is_correct' => false],
                ],
            ],
            [
                'id' => 70,
                'question' => 'Which interface is implemented by PHP exception and error objects?',
                'answers' => [
                    ['id' => 208, 'text' => 'Throwable', 'is_correct' => true],
                    ['id' => 209, 'text' => 'Exceptionable', 'is_correct' => false],
                    ['id' => 210, 'text' => 'ErrorHandler', 'is_correct' => false],
                ],
            ],
            [
                'id' => 71,
                'question' => 'Which operator suppresses error reporting for an expression?',
                'answers' => [
                    ['id' => 211, 'text' => '!', 'is_correct' => false],
                    ['id' => 212, 'text' => '~', 'is_correct' => false],
                    ['id' => 213, 'text' => '@', 'is_correct' => true],
                ],
            ],
            [
                'id' => 72,
                'question' => 'Which function sets a custom error handler?',
                'answers' => [
                    ['id' => 214, 'text' => 'handle_error()', 'is_correct' => false],
                    ['id' => 215, 'text' => 'set_error_handler()', 'is_correct' => true],
                    ['id' => 216, 'text' => 'register_error()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 73,
                'question' => 'Which directive enables strict scalar type checking for calls made from a file?',
                'answers' => [
                    ['id' => 217, 'text' => 'strict_types(true);', 'is_correct' => false],
                    ['id' => 218, 'text' => 'use strict;', 'is_correct' => false],
                    ['id' => 219, 'text' => 'declare(strict_types=1);', 'is_correct' => true],
                ],
            ],
            [
                'id' => 74,
                'question' => 'Which type declaration permits either an integer or a string?',
                'answers' => [
                    ['id' => 220, 'text' => 'int,string', 'is_correct' => false],
                    ['id' => 221, 'text' => 'int or string', 'is_correct' => true],
                    ['id' => 222, 'text' => 'int&string', 'is_correct' => false],
                ],
            ],
            [
                'id' => 75,
                'question' => 'Which symbol denotes a nullable type declaration?',
                'answers' => [
                    ['id' => 223, 'text' => '~', 'is_correct' => false],
                    ['id' => 224, 'text' => '!', 'is_correct' => false],
                    ['id' => 225, 'text' => '?', 'is_correct' => true],
                ],
            ],
            [
                'id' => 76,
                'question' => 'Which return type indicates a function returns no value?',
                'answers' => [
                    ['id' => 226, 'text' => 'null', 'is_correct' => false],
                    ['id' => 227, 'text' => 'void', 'is_correct' => true],
                    ['id' => 228, 'text' => 'empty', 'is_correct' => false],
                ],
            ],
            [
                'id' => 77,
                'question' => 'Which return type indicates a function never returns normally?',
                'answers' => [
                    ['id' => 229, 'text' => 'never', 'is_correct' => true],
                    ['id' => 230, 'text' => 'void', 'is_correct' => false],
                    ['id' => 231, 'text' => 'false', 'is_correct' => false],
                ],
            ],
            [
                'id' => 78,
                'question' => 'Which keyword declares a class constant?',
                'answers' => [
                    ['id' => 232, 'text' => 'define', 'is_correct' => false],
                    ['id' => 233, 'text' => 'constant', 'is_correct' => false],
                    ['id' => 234, 'text' => 'const', 'is_correct' => true],
                ],
            ],
            [
                'id' => 79,
                'question' => 'Which function defines a global constant at runtime?',
                'answers' => [
                    ['id' => 235, 'text' => 'define()', 'is_correct' => true],
                    ['id' => 236, 'text' => 'const()', 'is_correct' => false],
                    ['id' => 237, 'text' => 'constant_set()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 80,
                'question' => 'Which keyword declares an immutable property that can be initialized once?',
                'answers' => [
                    ['id' => 238, 'text' => 'readonly', 'is_correct' => true],
                    ['id' => 239, 'text' => 'final', 'is_correct' => false],
                    ['id' => 240, 'text' => 'immutable', 'is_correct' => false],
                ],
            ],
            [
                'id' => 81,
                'question' => 'Which PHP feature allows constructor parameters to become properties automatically?',
                'answers' => [
                    ['id' => 241, 'text' => 'Constructor property promotion', 'is_correct' => true],
                    ['id' => 242, 'text' => 'Property hoisting', 'is_correct' => false],
                    ['id' => 243, 'text' => 'Implicit inheritance', 'is_correct' => false],
                ],
            ],
            [
                'id' => 82,
                'question' => 'Which syntax declares an arrow function?',
                'answers' => [
                    ['id' => 244, 'text' => 'fn($x) => $x * 2', 'is_correct' => true],
                    ['id' => 245, 'text' => 'lambda $x => $x * 2', 'is_correct' => false],
                    ['id' => 246, 'text' => 'function($x) -> $x * 2', 'is_correct' => false],
                ],
            ],
            [
                'id' => 83,
                'question' => 'Which keyword imports variables into an anonymous function by lexical scope?',
                'answers' => [
                    ['id' => 247, 'text' => 'global', 'is_correct' => false],
                    ['id' => 248, 'text' => 'with', 'is_correct' => false],
                    ['id' => 249, 'text' => 'use', 'is_correct' => true],
                ],
            ],
            [
                'id' => 84,
                'question' => 'Which keyword yields a value from a generator?',
                'answers' => [
                    ['id' => 250, 'text' => 'return', 'is_correct' => false],
                    ['id' => 251, 'text' => 'emit', 'is_correct' => false],
                    ['id' => 252, 'text' => 'yield', 'is_correct' => true],
                ],
            ],
            [
                'id' => 85,
                'question' => 'What does a generator function return when invoked?',
                'answers' => [
                    ['id' => 253, 'text' => 'A Generator object', 'is_correct' => true],
                    ['id' => 254, 'text' => 'A string', 'is_correct' => false],
                    ['id' => 255, 'text' => 'An array', 'is_correct' => false],
                ],
            ],
            [
                'id' => 86,
                'question' => 'Which built-in interface supports iteration with foreach?',
                'answers' => [
                    ['id' => 256, 'text' => 'Countable', 'is_correct' => false],
                    ['id' => 257, 'text' => 'Serializable', 'is_correct' => false],
                    ['id' => 258, 'text' => 'Traversable', 'is_correct' => true],
                ],
            ],
            [
                'id' => 87,
                'question' => 'Which interface allows an object to be used with count()?',
                'answers' => [
                    ['id' => 259, 'text' => 'Countable', 'is_correct' => true],
                    ['id' => 260, 'text' => 'Stringable', 'is_correct' => false],
                    ['id' => 261, 'text' => 'Iterator', 'is_correct' => false],
                ],
            ],
            [
                'id' => 88,
                'question' => 'Which interface enables array-style access to an object?',
                'answers' => [
                    ['id' => 262, 'text' => 'ArrayLike', 'is_correct' => false],
                    ['id' => 263, 'text' => 'ArrayAccess', 'is_correct' => true],
                    ['id' => 264, 'text' => 'IterableAccess', 'is_correct' => false],
                ],
            ],
            [
                'id' => 89,
                'question' => 'Which built-in function checks if an object is an instance of a class or interface?',
                'answers' => [
                    ['id' => 265, 'text' => 'is_class()', 'is_correct' => false],
                    ['id' => 266, 'text' => 'object_type()', 'is_correct' => false],
                    ['id' => 267, 'text' => 'is_a()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 90,
                'question' => 'Which operator checks an object\'s class or interface relationship?',
                'answers' => [
                    ['id' => 268, 'text' => 'typeof', 'is_correct' => false],
                    ['id' => 269, 'text' => 'is', 'is_correct' => false],
                    ['id' => 270, 'text' => 'instanceof', 'is_correct' => true],
                ],
            ],
            [
                'id' => 91,
                'question' => 'Which function returns the class name of an object?',
                'answers' => [
                    ['id' => 271, 'text' => 'typeof()', 'is_correct' => false],
                    ['id' => 272, 'text' => 'class_name()', 'is_correct' => false],
                    ['id' => 273, 'text' => 'get_class()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 92,
                'question' => 'Which keyword declares an enumeration?',
                'answers' => [
                    ['id' => 274, 'text' => 'enumeration', 'is_correct' => false],
                    ['id' => 275, 'text' => 'choice', 'is_correct' => false],
                    ['id' => 276, 'text' => 'enum', 'is_correct' => true],
                ],
            ],
            [
                'id' => 93,
                'question' => 'Which keyword is used to match a value against arms and return a result?',
                'answers' => [
                    ['id' => 277, 'text' => 'select', 'is_correct' => false],
                    ['id' => 278, 'text' => 'match', 'is_correct' => true],
                    ['id' => 279, 'text' => 'switch', 'is_correct' => false],
                ],
            ],
            [
                'id' => 94,
                'question' => 'Does match use strict comparison in PHP?',
                'answers' => [
                    ['id' => 280, 'text' => 'Yes', 'is_correct' => true],
                    ['id' => 281, 'text' => 'No', 'is_correct' => false],
                    ['id' => 282, 'text' => 'Only for strings', 'is_correct' => false],
                ],
            ],
            [
                'id' => 95,
                'question' => 'Which operator performs null-safe property or method access?',
                'answers' => [
                    ['id' => 283, 'text' => '?->', 'is_correct' => true],
                    ['id' => 284, 'text' => '->?', 'is_correct' => false],
                    ['id' => 285, 'text' => '??->', 'is_correct' => false],
                ],
            ],
            [
                'id' => 96,
                'question' => 'Which function includes a file and fails fatally if it cannot be loaded?',
                'answers' => [
                    ['id' => 286, 'text' => 'readfile', 'is_correct' => false],
                    ['id' => 287, 'text' => 'include', 'is_correct' => false],
                    ['id' => 288, 'text' => 'require', 'is_correct' => true],
                ],
            ],
            [
                'id' => 97,
                'question' => 'Which construct includes a file at most once?',
                'answers' => [
                    ['id' => 289, 'text' => 'include_always', 'is_correct' => false],
                    ['id' => 290, 'text' => 'require_once', 'is_correct' => true],
                    ['id' => 291, 'text' => 'require', 'is_correct' => false],
                ],
            ],
            [
                'id' => 98,
                'question' => 'Which function hashes a password using a supported password algorithm?',
                'answers' => [
                    ['id' => 292, 'text' => 'md5()', 'is_correct' => false],
                    ['id' => 293, 'text' => 'base64_encode()', 'is_correct' => false],
                    ['id' => 294, 'text' => 'password_hash()', 'is_correct' => true],
                ],
            ],
            [
                'id' => 99,
                'question' => 'Which function verifies a password against a password_hash result?',
                'answers' => [
                    ['id' => 295, 'text' => 'password_check()', 'is_correct' => false],
                    ['id' => 296, 'text' => 'password_verify()', 'is_correct' => true],
                    ['id' => 297, 'text' => 'hash_equals()', 'is_correct' => false],
                ],
            ],
            [
                'id' => 100,
                'question' => 'Which function generates cryptographically secure random integers?',
                'answers' => [
                    ['id' => 298, 'text' => 'mt_rand()', 'is_correct' => false],
                    ['id' => 299, 'text' => 'random_int()', 'is_correct' => true],
                    ['id' => 300, 'text' => 'rand()', 'is_correct' => false],
                ],
            ],
        ];
    }
}
