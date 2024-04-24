<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Command;

use JR\ChefsDiary\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

#[AsCommand(
    name: 'app:generate-key',
    description: 'Generates a new APP_KEY'
)]
class GenerateAppKeyCommand extends Command
{
    public function __construct(private readonly Config $config)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hasKey = $this->config->get('app_key');

        if ($hasKey) {
            $question = new ConfirmationQuestion(
                'Generating a new APP_KEY will invalidate any signatures associated with the old key. Are you sure you want to proceed? (y/n)',
                false
            );

            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');

            if (!$helper instanceof QuestionHelper) {
                throw new \RuntimeException('Symfony QuestionHelper not found');
            }


            if (!$helper->ask($input, $output, $question)) {
                return Command::SUCCESS;
            }
        }

        $key = base64_encode(random_bytes(32));

        $envFilePath = __DIR__ . '/../../.env';

        if (!file_exists($envFilePath)) {
            throw new \RuntimeException('.env file not found');
        }

        $envFileContent = file_get_contents($envFilePath);

        $pattern = '/^APP_KEY=.*/m';

        if (preg_match($pattern, $envFileContent)) {
            $envFileContent = preg_replace($pattern, 'APP_KEY=' . $key, $envFileContent);
        } else {
            $envFileContent .= PHP_EOL . 'APP_KEY=' . $key;
        }

        file_put_contents($envFilePath, $envFileContent);

        $output->writeln('New APP_KEY has been generated & saved');

        return Command::SUCCESS;
    }
}