-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 04:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizmaticgrammar`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `COMMENT_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `QUIZ_ID` int(11) DEFAULT NULL,
  `COMMENT_TEXT` text DEFAULT NULL,
  `PARENT_COMMENT_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`COMMENT_ID`, `USER_ID`, `QUIZ_ID`, `COMMENT_TEXT`, `PARENT_COMMENT_ID`) VALUES
(14, 7, 11, 'yo', NULL),
(16, 1, 11, 'Done', 14);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FEEDBACK_ID` int(11) NOT NULL,
  `FEEDBACK_TEXT` text NOT NULL,
  `USER_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FEEDBACK_ID`, `FEEDBACK_TEXT`, `USER_ID`) VALUES
(4, 'Please give a harder question.', 6),
(5, 'Ha', 7);

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `LEADERBOARD_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `QUIZ_ID` int(11) DEFAULT NULL,
  `LEADERBOARD_SCORE` int(11) DEFAULT NULL,
  `LEADERBOARD_RANK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaderboard`
--

INSERT INTO `leaderboard` (`LEADERBOARD_ID`, `USER_ID`, `QUIZ_ID`, `LEADERBOARD_SCORE`, `LEADERBOARD_RANK`) VALUES
(10, 2, 11, 150, 1),
(11, 6, 9, 1000, 1),
(12, 7, 11, 50, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `QUESTION_ID` int(11) NOT NULL,
  `QUIZ_ID` int(11) DEFAULT NULL,
  `QUESTION_NO` int(11) DEFAULT NULL,
  `QUESTION_CONTENT` text DEFAULT NULL,
  `QUESTION_OPTION1` text DEFAULT NULL,
  `QUESTION_OPTION2` text DEFAULT NULL,
  `QUESTION_OPTION3` text DEFAULT NULL,
  `QUESTION_OPTION4` text DEFAULT NULL,
  `QUESTION_CORRECT_OPTION` char(1) DEFAULT NULL,
  `QUESTION_EXPLANATION` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`QUESTION_ID`, `QUIZ_ID`, `QUESTION_NO`, `QUESTION_CONTENT`, `QUESTION_OPTION1`, `QUESTION_OPTION2`, `QUESTION_OPTION3`, `QUESTION_OPTION4`, `QUESTION_CORRECT_OPTION`, `QUESTION_EXPLANATION`) VALUES
(1, 1, 1, 'John ________ in San Diego for the past 3 years (and he still lives there).', 'has lived', 'lived', 'livess', 'has been living', 'A', 'Present perfect is used for actions that started in the past and continue to the present. \"For the past 3 years\" shows it is ongoing.'),
(2, 1, 2, 'My brother ________ in San Diego up until last year.', 'lives', 'has lived', 'was living', 'lived', 'C', 'Past continuous describes a temporary action or situation that was happening before it ended. \"Up until last year\" means it is over now.'),
(3, 1, 3, 'I worked as a graphic designer before I ________ to England.', 'came', 'have come', 'was coming', 'had come', 'A', 'Past simple is used for completed actions. Both \"worked\" and \"came\" are events in the past, so they match.'),
(4, 1, 4, '________ reading the paper yet?', 'Were you finished', 'Have you finished', 'Are you finishing', 'Did you finish', 'B', 'Present perfect goes with \"yet\" when asking if something has been completed recently or just now.'),
(5, 1, 5, 'I ________ in love three times in my life.', 'have been', 'was being', 'was', 'fell', 'C', 'We use present perfect to describe life experiences up to the present. \"Three times in my life\" fits this perfectly.'),
(6, 1, 6, 'I ________ in love with her, but she did not feel the same way.', 'have been', 'was', 'was being', 'had been', 'B', 'Past simple shows a feeling or event that happened and ended. It is no longer true now, so we do not use present perfect.'),
(7, 1, 7, 'Frank ________ tennis for three years when he was at school.', 'was playing', 'has played', 'plays', 'played', 'D', 'Past simple fits because it refers to a completed action during a specific past time - when he was in school.'),
(8, 1, 8, '________ me last night?', 'Have you called', 'Did you call', 'Had you called', 'Were you calling', 'B', 'Past simple works with \"last night,\" which is a definite time in the past. Present perfect does not fit here.'),
(9, 1, 9, 'I was at the club yesterday, but I _________ you.', 'have not seen', 'was not seeing', 'had not seen', 'did not see', 'D', 'Past simple again fits because \"yesterday\" is a specific time. Present perfect needs an unspecified time.'),
(10, 1, 10, 'I ________ this upset in many years!', 'have not been', 'was not', 'was not being', 'have never felt', 'A', 'Present perfect is used for something that has not happened during a period that extends to now - \"in many years.\"'),
(11, 2, 1, 'He ________ to get very angry. (to begin)', 'began', 'beginned', 'begins', 'begun', 'A', '\"Began\" is the simple past tense of \"begin.\" The sentence refers to a past habit, so \"began\" is correct. \"Begun\" is past participle and needs \"has/have.\"'),
(12, 2, 2, 'I ________ my glasses when I fell. (to break)', 'broked', 'broken', 'break', 'broke', 'D', '\"Broke\" is the simple past tense of \"break.\" It fits a completed action in the past. \"Broked\" is incorrect (not a real word).'),
(13, 2, 3, 'Those kids have ________ five windows playing baseball. (to break)', 'broke', 'broked', 'broken', 'breaked', 'C', 'The present perfect tense \"have broken\" uses the past participle \"broken.\" \"Broke\" is simple past, so it does not fit here.'),
(14, 2, 4, 'I have ________ studying French. (to begin)', 'beginning', 'began', 'beginned', 'begun', 'D', 'The present perfect tense \"have begun\" uses the past participle \"begun.\" \"Began\" is simple past and not correct here.'),
(15, 2, 5, 'He told me that he had ________ all the work himself. (to do)', 'did', 'done did', 'done', 'do', 'C', '\"Had done\" is the correct past perfect form. \"Did\" is simple past and cannot follow \"had.\" \"Doed\" and \"done did\" are incorrect forms.'),
(16, 2, 6, 'Have you ________ yet today? (to eat)', 'eated', 'eat', 'eaten', 'ate', 'C', 'The present perfect \"have eaten\" uses the past participle \"eaten.\" \"Ate\" is simple past, and \"eated\" is not a proper form.'),
(17, 2, 7, 'My father has ________ me to talk to you. (to forbid)', 'forbid', 'forbids', 'forbade', 'forbidden', 'D', '\"Has forbidden\" is present perfect; \"forbidden\" is the correct past participle of \"forbid.\" \"Forbade\" is the simple past form.'),
(18, 2, 8, 'I ________ him stealing that woman\'s purse. (to catch)', 'catching', 'catched', 'caught', 'catch', 'C', '\"Caught\" is the simple past of \"catch.\" The event is in the past, so \"caught\" is correct. \"Catched\" is incorrect.'),
(19, 2, 9, 'He must have ________ 8 beers last night. (to drink)', 'drank', 'done drunk', 'drink', 'drunk', 'D', '\"Must have drunk\" uses the past participle \"drunk.\" \"Drank\" is simple past, and \"dranken\" is not a valid form.'),
(20, 2, 10, 'I ________ down and broke my arm. (to fall)', 'fell', 'fallen', 'falled', 'fall', 'A', '\"Fell\" is the simple past of \"fall.\" \"Fallen\" is past participle and needs \"have/has.\" \"Falled\" is not a valid verb form.'),
(21, 3, 1, 'If I ________ you, I would apologize to her right away. (to be)', 'were', 'have been', 'was', 'am', 'A', '\"Were\" is used in the second conditional as the correct subjunctive form of \"to be\" for all subjects.'),
(22, 3, 2, 'If I run into her, I ________ her that you are looking for her. (to tell)', 'will tell', 'tell', 'told', 'would tell', 'A', '\"Will tell\" is used in the first conditional to describe a likely future action based on a present condition.'),
(23, 3, 3, 'If you ________ that again, I will call the police. (to do)', 'do', 'will do', 'to do', 'doing', 'A', '\"Do\" is the correct simple present form used in the if-clause of a first conditional sentence.'),
(24, 3, 4, 'He would never have asked her out on a date if she ________ him first. (to kiss)', 'did not kiss', 'had not kissed', 'has not kissed', 'will not kiss', 'B', '\"Had not kissed\" is the correct past perfect form used in third conditional sentences.'),
(25, 3, 5, 'If you were her, what ________? (to do)', 'will you do', 'do you do', 'would you do', 'did you do', 'C', '\"Would you do\" fits second conditional, describing a hypothetical situation and result.'),
(26, 3, 6, 'If she had not gone to England, she ________ Tom Hardy. (to meet)', 'would not have met', 'did not meet', 'would not meet', 'will not meet', 'A', '\"Would not have met\" is correct for third conditional, showing a past unreal situation.'),
(27, 3, 7, 'If it does not start snowing, we ________ skiing this evening. (to go)', 'will not go', 'do not go', 'would not go', 'not go', 'A', '\"Will not go\" is correct in the main clause of a first conditional sentence.'),
(28, 3, 8, 'If you had saved some money earlier, you ________ broke right now. (to be)', 'are not', 'would not have been', 'will not be', 'would not be', 'B', '\"Would not have been\" is correct because the sentence refers to a missed opportunity in the past.'),
(29, 3, 9, 'If I ________ at the airport so late, I would not have missed my flight. (to arrive)', 'would not arrive', 'did not arrive', 'had not arrived', 'arrive', 'C', '\"Had not arrived\" is used in third conditional to express a past unreal condition.'),
(30, 3, 10, 'If you buy one t-shirt, you ________ the second one free. (to get)', 'will get', 'would get', 'will have gotten', 'get', 'D', '\"Get\" is correct for zero conditional, showing a general truth or deal that is always true.'),
(31, 4, 1, 'By the time I am 60, I ________ (lose) all my hair.', 'will have lost', 'will lose', 'will be losing', 'will has lost', 'A', '\"Will have lost\" is future perfect, used to describe something that will be completed before a certain future time.'),
(32, 4, 2, 'I will not be able to talk to you in 15 minutes because I ________ (do) my homework.', 'will do', 'will have done', 'will be doing', 'will doing', 'C', '\"Will be doing\" is future continuous, used for an action in progress at a specific future time.'),
(33, 4, 3, 'By the time I get home, my wife ________ (eat) the whole cake.', 'will be eating', 'will eat', 'will have eaten', 'will has eaten', 'C', '\"Will have eaten\" is future perfect, showing the action will be completed before another future moment.'),
(34, 4, 4, 'I ________ (talk) to my son about his poor test results.', 'will be talking', 'will talk', 'will have talked', 'will talks', 'B', '\"Will talk\" is simple future, suitable for a scheduled or decided action in the near future.'),
(35, 4, 5, 'This time tomorrow, I ________ (swim) in the ocean.', 'will swim', 'will have swam', 'will be swimming', 'will swimming', 'C', '\"Will be swimming\" is future continuous, used for an activity happening at a future point in time.'),
(36, 4, 6, 'I ________ (see) you at 7.', 'will be seeing', 'will see', 'will have seen', 'will sees', 'B', '\"Will see\" is the correct simple future tense for a planned or scheduled future meeting.'),
(37, 4, 7, 'By the time the guests arrive, I ________ (clean) the dining room.', 'will be cleaning', 'will clean', 'will have cleaned', 'will has cleaned', 'C', '\"Will have cleaned\" is future perfect, for an action completed before another future event.'),
(38, 4, 8, 'I decided that I ________ (become) a doctor.', 'will be becoming', 'will become', 'will have become', 'will becomes', 'B', '\"Will become\" is simple future, the appropriate form after a present or past decision.'),
(39, 4, 9, 'I ________ (travel) for the next month.', 'will be traveling', 'will have traveled', 'will travel', 'will travels', 'A', '\"Will be traveling\" is future continuous, used to describe ongoing action during a future period.'),
(40, 4, 10, 'I am really tired today. I ________ (do) my exercises tomorrow.', 'will do', 'will have done', 'will be doing', 'will did', 'A', '\"Will do\" is simple future, used for decisions or promises about future actions.'),
(41, 5, 1, 'He got in the car, pulled ________ of the driveway, and drove away.', 'out', 'off', 'up', 'away', 'A', '\"Pull out\" is the correct phrasal verb for leaving a place in a vehicle, especially from a driveway or parking space.'),
(42, 5, 2, 'I cannot believe you pulled that ________! (= managed to achieve this)', 'up', 'off', 'in', 'out', 'B', '\"Pull off\" means to successfully do something difficult or unexpected.'),
(43, 5, 3, 'The other candidates have now pulled ________ of Johnson. = Johnson is now behind the other candidates.', 'out', 'ahead', 'off', 'up', 'B', '\"Pull ahead\" is used when someone goes in front of others in a race or competition.'),
(44, 5, 4, 'He drove the car to the gate. = He pulled ________ to the gate.', 'ahead', 'off', 'out', 'up', 'D', '\"Pull up\" means to bring a vehicle to a stop, often at a specific place.'),
(45, 5, 5, 'It was a close race until Peter pulled ________ from the other runners and won by a whole minute.', 'up', 'away', 'off', 'ahead', 'B', '\"Pull away\" means to start to move ahead or further from others, especially in competition.'),
(46, 5, 6, 'I am pulling ________ him. = I am hoping he will win/succeed/etc.', 'on', 'in', 'away', 'for', 'D', '\"Pull for\" someone means to support or cheer for them.'),
(47, 5, 7, 'He is pulling ________ $80,000 a year. = He is making $80,000 a year.', 'in', 'over', 'up', 'around', 'A', '\"Pull in\" means to earn or bring in money.'),
(48, 5, 8, 'The police officer pulled him ________ (= stopped him) because he was driving with his lights off.', 'through', 'off', 'around', 'over', 'D', '\"Pull over\" is the phrasal verb for moving a vehicle to the side of the road and stopping.'),
(49, 5, 9, 'I am sure she will pull ________. = I am sure she will recover, be well again, etc.', 'off', 'over', 'through', 'ahead', 'C', '\"Pull through\" means to recover from illness or a difficult situation.'),
(50, 5, 10, 'He signed up for the event, but had to pull ________ (= cancel) because he did not have time to go.', 'around', 'off', 'in', 'out', 'D', '\"Pull out\" means to withdraw or cancel participation in an event or plan.'),
(51, 6, 1, 'The plane took ________ ( = departed) at 7:00 AM.', 'off', 'on', 'in', 'out', 'A', '\"Took off\" means the plane departed from the ground.'),
(52, 6, 2, 'They took ________ ( = saw) a play while they were in Glasgow.', 'out', 'away', 'in', 'on', 'C', '\"Take in\" means to watch or attend something like a performance or event.'),
(53, 6, 3, 'He took ________ ( = started) smoking after his accident.', 'off', 'up', 'over', 'about', 'B', '\"Take up\" means to begin a new activity or habit.'),
(54, 6, 4, 'They will have to take ________ ( = compete against) another opponent.', 'in', 'after', 'on', 'away', 'C', '\"Take on\" means to accept a challenge or opponent.'),
(55, 6, 5, 'He really takes ________ his father. = He is really similar to his father.', 'after', 'on', 'in', 'over', 'A', '\"Take after\" means to resemble a family member in appearance or behavior.'),
(56, 6, 6, 'I will take it ________ with the boss. = I will speak to the boss about it.', 'about', 'up', 'on', 'off', 'B', '\"Take up (a matter)\" means to raise or discuss something with someone.'),
(57, 6, 7, 'I have to take ________. ( = leave)', 'off', 'over', 'around', 'on', 'A', '\"Take off\" is an informal way to say you are leaving.'),
(58, 6, 8, 'After Bill was fired, John took ________ ( = assumed) his position.', 'away', 'in', 'out', 'over', 'D', '\"Take over\" means to assume control or responsibility, especially in a role or job.'),
(59, 6, 9, 'I will take you ________ on your offer. = I will accept your offer.', 'away', 'out', 'up', 'on', 'C', '\"Take someone up on an offer\" means to accept it.'),
(60, 6, 10, 'We took ________ ( = adopted, brought home) several kittens while we were living in Scotland.', 'up', 'after', 'off', 'in', 'D', '\"Take in\" can mean to adopt or give shelter, especially for animals.'),
(61, 7, 1, 'How am I going to come ________ ( = find, get) all that money?', 'up with', 'up to', 'over', 'by', 'A', '\"Come up with\" means to think of or produce something like an idea or a solution.'),
(62, 7, 2, 'He came ________ ( = inherited) a lot of money when his grandfather died.', 'on', 'into', 'by', 'out', 'B', '\"Come into\" is used when someone inherits money or property.'),
(63, 7, 3, 'I am pretty sure he was coming ________ me ( = flirting with me) last night.', 'into', 'up with', 'on to', 'for', 'C', '\"Come on to\" means to flirt with someone.'),
(64, 7, 4, 'Mark\'s uncle is coming ________ ( = releasing) a new book next month.', 'out with', 'up to', 'into', 'at', 'A', '\"Come out with\" means to publish or release something like a product or book.'),
(65, 7, 5, 'He came ________ me ( = attacked me) with a knife.', 'by', 'up with', 'to', 'at', 'D', '\"Come at\" means to attack someone physically or verbally.'),
(66, 7, 6, 'I think I am coming ________ something. ( = I think I am getting sick.)', 'into', 'down with', 'on to', 'off', 'B', '\"Come down with\" means to start showing signs of an illness.'),
(67, 7, 7, 'She came ________ ( = regained consciousness) about half an hour after she passed out.', 'off', 'over', 'up', 'to', 'D', '\"Come to\" means to regain consciousness.'),
(68, 7, 8, 'I came ________ ( = found, by chance) some old magazines while I was cleaning my room.', 'up with', 'into', 'across', 'along', 'C', '\"Come across\" means to find something by chance.'),
(69, 7, 9, 'You have to come ________ ( = think of) a better excuse than that.', 'up with', 'around to', 'along with', 'out with', 'A', '\"Come up with\" means to invent or create an idea or excuse.'),
(70, 7, 10, 'He came ________ as ( = made the impression of being) arrogant.', 'over', 'off', 'on', 'in', 'B', '\"Come off as\" means to give a certain impression to others.'),
(71, 8, 1, 'P1: Are you still going out with Mary? P2: No, we broke ________ last week.', 'out', 'up', 'in', 'over', 'B', '\"Broke up\" means to end a romantic relationship.'),
(72, 8, 2, 'The thieves broke ________ the bank and stole $1,000,000.', 'away', 'in', 'around', 'into', 'B', '\"Broke in\" means to enter a building illegally by force.'),
(73, 8, 3, 'An epidemic of Ebola broke ________ in West Africa last year.', 'in', 'out', 'over', 'off', 'C', '\"Broke out\" is used for something dangerous suddenly starting, like war or disease.'),
(74, 8, 4, 'He broke ________ the engagement after he realized that his fiancee did not love him.', 'off', 'in', 'on', 'up', 'D', '\"Broke off\" means to cancel or end something, like an engagement or agreement.'),
(75, 8, 5, 'The police officer grabbed him, but the thief broke ________ and escaped.', 'up', 'over', 'in', 'away', 'D', '\"Broke away\" means to escape from being held or caught.'),
(76, 8, 6, 'My brother broke ________ a piece of chocolate and gave it to me.', 'up', 'out', 'on', 'off', 'D', '\"Broke off\" means to separate a piece from something larger.'),
(77, 8, 7, 'The sun broke ________ the clouds after the storm.', 'through', 'out', 'in', 'along', 'A', '\"Broke through\" means to suddenly appear from behind something, like the sun through clouds.'),
(78, 8, 8, 'The thieves broke ________ the safe and stole all the money.', 'open', 'up', 'out', 'down', 'A', '\"Broke open\" means to force something to open.'),
(79, 8, 9, 'My car broke ________ ( = stopped functioning) two months after I bought it.', 'up', 'down', 'out', 'off', 'B', '\"Broke down\" means to stop working due to mechanical failure.'),
(80, 8, 10, 'He broke ________ ( = stopped) the fight.', 'on', 'up', 'in', 'off', 'B', '\"Broke up\" means to stop a fight or gathering.'),
(81, 9, 1, 'I am not capable ________________ that type of behavior.', 'of', 'in', 'at', 'by', 'A', '\"Capable of\" is the correct prepositional phrase to express ability or suitability.'),
(82, 9, 2, 'Smoking is bad ________________ you.', 'at', 'for', 'on', 'with', 'B', '\"Bad for\" is the correct expression when talking about something harmful to your health.'),
(83, 9, 3, 'I am so angry ________________ this!', 'to', 'with', 'about', 'at', 'C', '\"Angry about\" is used when you are angry regarding a situation or event.'),
(84, 9, 4, 'I am so angry _______________ you!', 'about', 'to', 'in', 'with', 'D', '\"Angry with\" is used when someone is angry at a person.'),
(85, 9, 5, 'She was dressed _______________ pink.', 'in', 'with', 'on', 'at', 'A', '\"Dressed in\" is the correct phrase for wearing a color or item.'),
(86, 9, 6, 'This restaurant is famous _______________ its mussels.', 'on', 'for', 'in', 'by', 'B', '\"Famous for\" means known for a particular reason.'),
(87, 9, 7, 'George is married _______________ a German woman.', 'with', 'at', 'in', 'to', 'D', '\"Married to\" is the correct prepositional phrase for expressing whom someone is married to.'),
(88, 9, 8, 'Are you afraid ________________ him?', 'of', 'to', 'with', 'at', 'A', '\"Afraid of\" is used to express fear toward something or someone.'),
(89, 9, 9, 'I am so proud _______________ you!', 'of', 'to', 'in', 'by', 'A', '\"Proud of\" is the correct expression to show admiration or pride.'),
(90, 9, 10, 'We are not associated _______________ that company.', 'on', 'to', 'with', 'about', 'C', '\"Associated with\" means connected or related to someone or something.'),
(91, 10, 1, 'I am off for two weeks ________ August.', 'at', 'on', 'in', 'during', 'C', '\"In August\" is the correct preposition for months.'),
(92, 10, 2, 'The dog is asleep ________ the tree.', 'under', 'against', 'over', 'by', 'A', '\"Under the tree\" indicates the dog is beneath the tree.'),
(93, 10, 3, 'I have lived in this apartment ________ 2022.', 'in', 'since', 'at', 'from', 'B', '\"Since\" is used with specific starting points in time.'),
(94, 10, 4, 'My friend ________ work is from Japan.', 'at', 'in', 'with', 'from', 'D', '\"My friend from work\" is the correct expression to indicate someone you know through work.'),
(95, 10, 5, 'I wake up ________ 6 AM every morning.', 'at', 'on', 'around', 'in', 'A', '\"At\" is used for specific times of the day.'),
(96, 10, 6, 'Thank you ________ telling me about this.', 'for', 'in', 'with', 'to', 'A', '\"Thank you for\" is the correct preposition to express gratitude.'),
(97, 10, 7, 'Who did you buy this ________?', 'from', 'in', 'on', 'to', 'A', '\"Buy from\" indicates the person you bought something from.'),
(98, 10, 8, 'The factory workers have been ________ strike since January.', 'in', 'about', 'on', 'off', 'C', '\"On strike\" is the correct phrase used when workers refuse to work.'),
(99, 10, 9, '________ you and me, I do not think he is a very good teacher.', 'Through', 'Between', 'Among', 'Under', 'B', '\"Between you and me\" is a common phrase used to express a secret or opinion.'),
(100, 10, 10, 'P1: Did you do it ________ purpose? P2: No, it was an accident!', 'in', 'by', 'at', 'on', 'D', '\"On purpose\" means intentionally or deliberately.'),
(101, 11, 1, '________ do you live? → I live in Tokyo.', 'Where', 'Who', 'How', 'When', 'A', '\"Where\" is used to ask about a place or location.'),
(102, 11, 2, '________ are you going to the concert? → I am going to the concert next week.', 'What', 'When', 'Why', 'How', 'B', '\"When\" is used to ask about time.'),
(103, 11, 3, '________ is Nancy reading? → She is reading a newspaper.', 'What', 'When', 'Who', 'How', 'A', '\"What\" is used to ask about an object or thing.'),
(104, 11, 4, '________ are they speaking with? → They are speaking with their friends.', 'Why', 'What', 'Whom', 'Where', 'C', '\"Whom\" is used as the object of a verb or preposition, especially in formal English.'),
(105, 11, 5, '________ are you talking about? → We are talking about the weather.', 'Whom', 'What', 'Where', 'Why', 'B', '\"What\" is used to ask for specific information about a subject or thing.'),
(106, 11, 6, '________ will you be home? → I will be home in two hours.', 'When', 'Where', 'What', 'Who', 'A', '\"When\" is the correct WH-word for asking about time.'),
(107, 11, 7, '________ did Mary see at the beach? → Mary saw Thomas at the beach.', 'Where', 'Who', 'When', 'What', 'B', '\"Who\" is used to ask about a person as the subject of a sentence.'),
(108, 11, 8, '________ did you go last night? → We went to a cafe.', 'Why', 'When', 'Where', 'Who', 'C', '\"Where\" is the appropriate word for asking about a place.'),
(109, 11, 9, '________ did you tell her? → I told her yesterday.', 'Who', 'What', 'Where', 'When', 'D', '\"When\" is used to ask about time or date.'),
(110, 11, 10, '________ did you tell her? → I told her the truth.', 'What', 'Where', 'When', 'Who', 'A', '\"What\" is used to ask about information or facts.');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `QUIZ_ID` int(11) NOT NULL,
  `QUIZ_TITLE` varchar(255) DEFAULT NULL,
  `QUIZ_CATEGORY` varchar(100) DEFAULT NULL,
  `QUIZ_DIFFICULTY` varchar(50) DEFAULT NULL,
  `QUIZ_DATE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`QUIZ_ID`, `QUIZ_TITLE`, `QUIZ_CATEGORY`, `QUIZ_DIFFICULTY`, `QUIZ_DATE`) VALUES
(1, 'Mixed Verbs', 'Verb Tenses', 'Intermediate', '2025-06-21'),
(2, 'Irregular Verbs', 'Verb Tenses', 'Easy', '2025-06-21'),
(3, 'Mixed Conditional Exercise', 'Verb Tenses', 'Easy', '2025-06-21'),
(4, 'Future Tenses', 'Verb Tenses', 'Advance', '2025-06-21'),
(5, 'Phrasal Verbs (TO PULL +)', 'Phrasal Verbs', 'Intermediate', '2025-06-21'),
(6, 'Phrasal Verbs (TO TAKE +)', 'Phrasal Verbs', 'Intermediate', '2025-06-21'),
(7, 'Phrasal Verbs (TO COME +)', 'Phrasal Verbs', 'Advance', '2025-06-21'),
(8, 'Phrasal Verbs (TO BREAK +)', 'Phrasal Verbs', 'Advance', '2025-06-21'),
(9, 'Adjectives + prepositions', 'Prepositions', 'Intermediate', '2025-06-21'),
(10, 'Mixed prepositions', 'Prepositions', 'Easy', '2025-06-21'),
(11, 'Who, When, Where, What', 'Other Grammar Topics', 'Easy', '2025-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `USER_NAME` varchar(20) DEFAULT NULL,
  `USER_PASSWORD` varchar(20) DEFAULT NULL,
  `USER_EMAIL` varchar(20) DEFAULT NULL,
  `USER_TYPE` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `USER_NAME`, `USER_PASSWORD`, `USER_EMAIL`, `USER_TYPE`) VALUES
(1, 'Admin', 'admin123', 'admin@gmail.com', 'administrator'),
(2, 'Aiman', 'aiman123', 'aiman@gmail.com', 'user'),
(3, 'Badrul', 'badrul123', 'badrul@gmail.com', 'user'),
(4, 'Amin', 'amin123', 'amin@gmail.com', 'user'),
(5, 'Kamal', 'kamal123', 'kamal@gmail.com', 'user'),
(6, 'Fariz', 'Abc', 'fariz@gmail.com', 'user'),
(7, 'Fadhli', '123', 'fadhli@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_comment`
--

CREATE TABLE `user_comment` (
  `USER_ID` int(11) NOT NULL,
  `COMMENT_ID` int(11) NOT NULL,
  `DATE_POST` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_comment`
--

INSERT INTO `user_comment` (`USER_ID`, `COMMENT_ID`, `DATE_POST`) VALUES
(1, 16, '2025-07-08'),
(7, 14, '2025-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz`
--

CREATE TABLE `user_quiz` (
  `USERQUIZ_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `QUIZ_ID` int(11) DEFAULT NULL,
  `DATE_TAKEN` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_quiz`
--

INSERT INTO `user_quiz` (`USERQUIZ_ID`, `USER_ID`, `QUIZ_ID`, `DATE_TAKEN`) VALUES
(59, 2, 11, '2025-07-07'),
(60, 6, 9, '2025-07-07'),
(61, 6, 9, '2025-07-07'),
(62, 7, 11, '2025-07-08'),
(63, 7, 11, '2025-07-08'),
(64, 7, 11, '2025-07-08'),
(65, 7, 11, '2025-07-08'),
(66, 7, 11, '2025-07-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`COMMENT_ID`),
  ADD KEY `QUIZ_ID` (`QUIZ_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FEEDBACK_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`LEADERBOARD_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `QUIZ_ID` (`QUIZ_ID`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`QUESTION_ID`),
  ADD KEY `QUIZ_ID` (`QUIZ_ID`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`QUIZ_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Indexes for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD PRIMARY KEY (`USER_ID`,`COMMENT_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `user_comment_ibfk_2` (`COMMENT_ID`);

--
-- Indexes for table `user_quiz`
--
ALTER TABLE `user_quiz`
  ADD PRIMARY KEY (`USERQUIZ_ID`),
  ADD KEY `QUIZ_ID` (`QUIZ_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FEEDBACK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `LEADERBOARD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `QUESTION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `QUIZ_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_quiz`
--
ALTER TABLE `user_quiz`
  MODIFY `USERQUIZ_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `leaderboard_ibfk_2` FOREIGN KEY (`QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`);

--
-- Constraints for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`COMMENT_ID`) REFERENCES `comment` (`COMMENT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `user_quiz`
--
ALTER TABLE `user_quiz`
  ADD CONSTRAINT `user_quiz_ibfk_1` FOREIGN KEY (`QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`),
  ADD CONSTRAINT `user_quiz_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
