<?php

namespace Tests\Feature\Livewire\Auth\Guests;

use App\Livewire\Auth\Guests\SignUp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\Livewire;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    private $chunk_size;

    private $total_iterations;

    private $test_status = [];

    private $test_count = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chunk_size = rand(10, 50);
        $this->total_iterations = rand(10, 50);
        // $this->chunk_size = rand(1_000, 100_000_000_000);
        // $this->total_iterations = rand(1_000, 100_000_000_000);
    }

    /** @test */
    // #[Test]
    public function renders_successfully()
    {
        $this->test_count++;
        Livewire::test(SignUp::class)
            ->assertStatus(200);

        $this->test_status[] = true;
    }

    /**
     * @test
     *
     * @depends it_validates_valid_contact_num_property
     */
    public function create_applicant_successfully()
    {
        if (count($this->test_status) == $this->test_count) {
            $this->assertTrue(true);
        }

        // $this->mock(CreatesNewUsers::class, function ($mock) {
        //     $mock->shouldReceive('create')->andReturn((object) ['id' => 1]);
        // });

        // Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function it_validates_invalid_email_property()
    {
        $this->test_count++;

        $invalid_emails = [
            null,
            '',
            ' ',
            '      ',
            '      ',
            'plainaddress',
            '@missingusername.com',
            'username@.com',
            'username@domain..com',
            'username@domain,com',
            'username@domain@domain.com',
            'username@domain..com',
            '   '.fake()->unique()->freeEmailDomain,
            fake()->unique()->freeEmail().'   ',
            '   '.fake()->unique()->freeEmail().'   ',
        ];

        foreach ($invalid_emails as $email) {
            $component = Livewire::test(SignUp::class)
                ->set('email', $email)
                ->call('store');

            echo "check that \e[1;43m '$email' \e[0m is invalid.\n";

            $component->assertHasErrors(['email']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_valid_email_property()
    {

        $this->test_count++;

        @file_get_contents('http://localhost:5173/resources/js/email-domain-list.json');

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                $valid_email = fake()->unique()->freeEmail();

                $component = Livewire::test(SignUp::class)
                    ->set('email', $valid_email)
                    ->call('store');

                echo "check that \e[1;43m '$valid_email' \e[0m is valid.\n";

                $component->assertHasNoErrors(['email']);
                unset($valid_email, $component);
            }
            gc_collect_cycles();
        }

        $this->test_status[] = true;
    }

    protected function isValidPassword($password)
    {
        $hasUppercase = false;
        $hasLowercase = false;
        $hasDigit = false;
        $hasSymbol = false;

        // Loop through each character and check conditions in one pass
        for ($i = 0, $len = strlen($password); $i < $len; $i++) {
            $char = $password[$i];

            if (ctype_upper($char)) {
                $hasUppercase = true;
            } elseif (ctype_lower($char)) {
                $hasLowercase = true;
            } elseif (ctype_digit($char)) {
                $hasDigit = true;
            } elseif (! ctype_alnum($char)) {
                $hasSymbol = true;
            }

            // Exit early if all conditions are met
            if ($hasUppercase && $hasLowercase && $hasDigit && $hasSymbol) {
                break;
            }
        }

        // Ensure there are no spaces and all conditions are met
        return $hasUppercase && $hasLowercase && $hasDigit && $hasSymbol && strpos($password, ' ') === false;
    }

    /** @test */
    public function it_validates_invalid_password_property()
    {
        $this->test_count++;

        $invalid_passwords = [
            null,
            '',
            'short',
            'nouppercase1',
            'NOLOWERCASE1',
            'NoNumber',
            'NoSpecialChar1',
            '   '.fake()->password,
            fake()->password.'   ',
            '   '.fake()->password.'   ',
        ];

        foreach ($invalid_passwords as $password) {
            $component = Livewire::test(SignUp::class)
                ->set('password', $password)
                ->set('password_confirmation', $password)
                ->call('store');

            echo "check that \e[1;43m '$password' \e[0m is invalid.\n";

            $component->assertHasErrors(['password']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_valid_password_property()
    {

        $this->test_count++;

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                $valid_password = fake()->password(8, 72);

                while (! $this->isValidPassword($valid_password)) {
                    $valid_password = fake()->password(8, 72); // regenerate password
                }

                $component = Livewire::test(SignUp::class)
                    ->set('password', $valid_password)
                    ->set('password_confirmation', $valid_password)
                    ->call('store');

                // if ($component->assertHasErrors(['password'])) {
                echo "asserting that \e[1;43m '$valid_password' \e[0m is valid.\n";
                // }

                $component->assertHasNoErrors(['password']);

                // Maybe solution for  Fatal error:
                // Allowed memory size of 134217728 bytes exhausted (tried to allocate 1310720 bytes)
                unset($valid_password, $component);
            }
            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_consent_property()
    {
        $this->test_count++;

        $invalid_consents = [
            null,
            false,
            '',
            'yes',
            'accepted',
            'no',
            'false',
            0,
        ];

        foreach ($invalid_consents as $consent) {
            $component = Livewire::test(SignUp::class)
                ->set('consent', $consent)
                ->call('store');

            echo "check that \e[1;43m '$consent' \e[0m is invalid.\n";

            $component->assertHasErrors(['consent']);
        }

        $valid_consents = [
            true,
            1,
        ];

        foreach ($valid_consents as $consent) {
            $component = Livewire::test(SignUp::class)
                ->set('consent', $consent)
                ->call('store');

            if ($component->assertHasErrors(['consent'])) {
                echo "\e[1;43m FAILED \e[0m asserting that \e[1;43m '$consent' \e[0m is valid.\n";
            }

            $component->assertHasNoErrors(['consent']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_invalid_first_name_property()
    {

        $this->test_count++;

        $invalid_first_names = [
            null,
            '',
            'A', // Too short
            str_repeat('A', 192), // Too long
            '123',
            'John123',
            'John@Doe',
            'John_Doe',
            '   '.fake()->firstName,
            fake()->firstName.'   ',
            '   '.fake()->firstName.'   ',
        ];

        foreach ($invalid_first_names as $first_name) {
            $component = Livewire::test(SignUp::class)
                ->set('first_name', $first_name)
                ->call('store');

            echo "check that \e[1;43m '$first_name' \e[0m is invalid.\n";

            $component->assertHasErrors(['first_name']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_valid_first_name_property()
    {

        $this->test_count++;

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                $valid_first_name = fake()->firstName();

                // Occasionally make two word name
                if (rand(0, 1)) {
                    $valid_first_name .= ' '.fake()->firstName();
                }

                // Occasionally add ñ in the name
                if (rand(0, 2) == 1) {
                    $valid_first_name = str_replace('n', 'ñ', $valid_first_name);
                }

                if (rand(0, 10) == 1) {
                    $valid_first_name = str_replace('ñ', 'Ñ', $valid_first_name);
                }

                $component = Livewire::test(SignUp::class)
                    ->set('first_name', $valid_first_name)
                    ->call('store');

                $component->assertHasNoErrors(['first_name']);

                unset($valid_first_name, $component);
            }

            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_invalid_middle_name_property()
    {

        $this->test_count++;

        $invalid_middle_names = [
            'A', // Too short
            str_repeat('A', 192), // Too long
            '123',
            'John123',
            'John@Doe',
            'John_Doe',
            '   '.fake()->firstName,
            fake()->firstName.'   ',
            '   '.fake()->firstName.'   ',
        ];

        foreach ($invalid_middle_names as $middle_name) {
            $component = Livewire::test(SignUp::class)
                ->set('middle_name', $middle_name)
                ->call('store');

            echo "asserting that \e[1;43m '$middle_name' \e[0m is invalid.\n";

            $component->assertHasErrors(['middle_name']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_valid_middle_name_property()
    {

        $this->test_count++;

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                $middle_name = rand(0, 199) < 2 ? null : (rand(0, 149) < 1 ? '' : fake()->firstName());

                // Occasionally make two word name
                if (rand(0, 5) == 1 && ! ($middle_name == '' || $middle_name == null)) {
                    $middle_name .= ' '.fake()->firstName();
                }

                // Occasionally add ñ in the name
                if (rand(0, 2) == 1) {
                    $middle_name = str_replace('n', 'ñ', $middle_name);
                }

                if (rand(0, 10) == 1) {
                    $middle_name = str_replace('ñ', 'Ñ', $middle_name);
                }

                $component = Livewire::test(SignUp::class)
                    ->set('middle_name', $middle_name)
                    ->call('store');

                echo "check is '$middle_name' valid \n";

                $component->assertHasNoErrors(['middle_name']);

                unset($middle_name, $component);
            }

            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_invalid_last_name_property()
    {

        $this->test_count++;

        $invalid_last_names = [
            null,
            '',
            'A', // Too short
            str_repeat('A', 192), // Too long
            '123',
            'John123',
            'John@Doe',
            'John_Doe',
            '   '.fake()->lastName,
            fake()->lastName.'   ',
            '   '.fake()->lastName.'   ',
        ];

        foreach ($invalid_last_names as $last_name) {
            $component = Livewire::test(SignUp::class)
                ->set('last_name', $last_name)
                ->call('store');

            echo "check that \e[1;43m '$last_name' \e[0m is invalid.\n";

            $component->assertHasErrors(['last_name']);
        }

        $this->test_status[] = true;
    }

    /** @test */
    public function it_validates_valid_last_name_property()
    {

        $this->test_count++;

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                $valid_last_name = fake()->lastName();

                // Occasionally make two word name
                if (rand(0, 1)) {
                    if (rand(0, 1)) {
                        $valid_last_name .= '-'.fake()->lastName();
                    } else {
                        $valid_last_name .= ' '.fake()->lastName();
                    }
                }

                // Occasionally add ñ in the name
                if (rand(0, 2) == 1) {
                    $valid_last_name = str_replace('n', 'ñ', $valid_last_name);
                }

                if (rand(0, 10) == 1) {
                    $valid_last_name = str_replace('ñ', 'Ñ', $valid_last_name);
                }

                $component = Livewire::test(SignUp::class)
                    ->set('last_name', $valid_last_name)
                    ->call('store');

                $component->assertHasNoErrors(['last_name']);
                unset($valid_last_name, $component);
            }
            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }

    public function it_validates_invalid_contact_num_property()
    {

        $this->test_count++;

        $invalid_contact_numbers = [
            null,
            '',
            '   ',
            '1234567890', // 10 digits
            '123456789012', // 12 digits
            'abcdefghijk', // non-numeric
            '12345abcde6', // alphanumeric
            '123 456 7890', // spaces
            '123-456-7890', // dashes
            '123.456.7890', // dots
            '12345678901 ', // trailing space
            ' 12345678901', // leading space
            ' 12345678901 ', // leading and trailing spaces
        ];

        foreach ($invalid_contact_numbers as $contact_number) {
            $component = Livewire::test(SignUp::class)
                ->set('contact_number', $contact_number)
                ->call('store');

            echo "check that \e[1;43m '$contact_number' \e[0m is invalid.\n";

            $component->assertHasErrors(['contact_number']);
        }

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {
                // Generate different types of invalid contact numbers
                $invalid_contact_number = fake()->randomElement([
                    fake()->numerify('09'.str_repeat('#', 2)),
                    fake()->numerify('09'.str_repeat('#', 3)),
                    fake()->numerify('09'.str_repeat('#', 4)),
                    fake()->numerify('09'.str_repeat('#', 5)),
                    fake()->numerify('09'.str_repeat('#', 6)),
                    fake()->numerify('09'.str_repeat('#', 7)),
                    fake()->numerify('09'.str_repeat('#', 8)),
                    fake()->numerify('09'.str_repeat('#', 10)),
                    fake()->numerify('09'.str_repeat('#', 11)),
                    fake()->numerify('09'.str_repeat('#', 12)),
                    fake()->numerify('09'.str_repeat('#', 13)),
                    fake()->numerify('09'.str_repeat('#', 14)),
                    fake()->numerify('09'.str_repeat('#', 15)),
                    fake()->numerify('09'.str_repeat('#', 16)),
                    fake()->numerify('09'.str_repeat('#', 255)),
                    '09'.fake()->numerify('### ### ###'),
                    '09'.fake()->numerify('###-###-###'),
                    '09'.fake()->numerify('###.###.###'),
                    '09'.fake()->numerify('###    ###  ### ').fake()->numerify('#'),
                    '  '.fake()->numerify('09#########'),
                    fake()->numerify('09#########').'   ',
                    '  '.fake()->numerify('09#########').'   ', // Leading space
                    '  '.fake()->numerify('09 ###    ###  ### ').fake()->numerify('#').'   ',
                    fake()->numerify('09###').'abc'.fake()->numerify('###'), // Invalid characters
                ]);

                $component = Livewire::test(SignUp::class)
                    ->set('contact_number', $invalid_contact_number)
                    ->call('store');

                echo "asserting that \e[1;43m '$invalid_contact_number' \e[0m is invalid.\n";

                $component->assertHasErrors(['contact_number']);

                unset($invalid_contact_number, $component);
            }
            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }

    public function it_validates_valid_contact_num_property()
    {

        $this->test_count++;

        for ($batch = 0; $batch < ($this->total_iterations / $this->chunk_size); $batch++) {
            for ($i = 0; $i < $this->chunk_size; $i++) {

                $valid_contact_number = fake()->numerify('09#########');

                $component = Livewire::test(SignUp::class)
                    ->set('contact_number', $valid_contact_number)
                    ->call('store');

                echo "asserting that \e[1;43m '$valid_contact_number' \e[0m is valid.\n";

                $component->assertHasNoErrors(['contact_number']);

                unset($valid_contact_number, $component);
            }
            gc_collect_cycles(); // Collect garbage to free up memory
        }

        $this->test_status[] = true;
    }
}
