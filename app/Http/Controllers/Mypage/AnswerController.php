<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnswerController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;

        $featuredQuestions = Question::featured()->get();
        $otherQuestions = Question::others()->get();
        $customQuestions = Question::customFor(Auth::id())->get();

        $answers = $profile->answers()->get()->keyBy('question_id');

        return view('mypage.answers.edit', compact(
            'profile', 'featuredQuestions', 'otherQuestions', 'customQuestions', 'answers',
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'answers' => ['array'],
            'answers.*' => ['nullable', 'string', 'max:500'],
        ], [
            'answers.*.max' => '回答は 500 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;
        $officialQuestionIds = Question::official()->pluck('id')->all();
        $input = $request->input('answers', []);

        DB::transaction(function () use ($profile, $officialQuestionIds, $input) {
            foreach ($officialQuestionIds as $qid) {
                $body = $input[$qid] ?? null;
                if ($body === null || trim($body) === '') {
                    $profile->answers()->where('question_id', $qid)->delete();
                } else {
                    $profile->answers()->updateOrCreate(
                        ['question_id' => $qid],
                        ['body' => $body, 'sort_order' => 0],
                    );
                }
            }
        });

        return redirect()->route('mypage.answers.edit')->with('status', 'answers-updated');
    }

    public function storeCustom(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:120'],
            'answer' => ['required', 'string', 'max:500'],
        ], [
            'body.required' => '質問文を入力してください。',
            'body.max' => '質問は 120 文字以内で入力してください。',
            'answer.required' => '回答を入力してください。',
            'answer.max' => '回答は 500 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;
        $maxSort = (int) Question::where('owner_user_id', Auth::id())->max('sort_order');

        DB::transaction(function () use ($validated, $profile, $maxSort) {
            $question = Question::create([
                'owner_user_id' => Auth::id(),
                'body' => $validated['body'],
                // カスタムは運営の sort_order と被らないよう 1000 以上に置く
                'sort_order' => max(1000, $maxSort + 1),
                'is_active' => true,
            ]);

            Answer::create([
                'profile_id' => $profile->id,
                'question_id' => $question->id,
                'body' => $validated['answer'],
                'sort_order' => 0,
            ]);
        });

        return redirect()->route('mypage.answers.edit')->with('status', 'custom-added');
    }

    public function updateCustom(Request $request, Question $question): RedirectResponse
    {
        $this->ensureOwner($question);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:120'],
            'answer' => ['required', 'string', 'max:500'],
        ], [
            'body.required' => '質問文を入力してください。',
            'body.max' => '質問は 120 文字以内で入力してください。',
            'answer.required' => '回答を入力してください。',
            'answer.max' => '回答は 500 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;

        DB::transaction(function () use ($validated, $profile, $question) {
            $question->update(['body' => $validated['body']]);
            $profile->answers()->updateOrCreate(
                ['question_id' => $question->id],
                ['body' => $validated['answer'], 'sort_order' => 0],
            );
        });

        return redirect()->route('mypage.answers.edit')->with('status', 'custom-updated');
    }

    public function destroyCustom(Question $question): RedirectResponse
    {
        $this->ensureOwner($question);

        // answers は questions の cascadeOnDelete で消える
        $question->delete();

        return redirect()->route('mypage.answers.edit')->with('status', 'custom-deleted');
    }

    private function ensureOwner(Question $question): void
    {
        abort_if($question->owner_user_id !== Auth::id(), 403);
    }
}
