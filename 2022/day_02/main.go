package main

import (
	"fmt"
	"io/ioutil"
	"strings"
)

var shape = map[string]string{
	"A": "Rock",
	"B": "Paper",
	"C": "Scissors",
	"X": "Rock",
	"Y": "Paper",
	"Z": "Scissors",
}

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	parts := strings.Split(string(content), "\n")
	total := 0

	for _, v := range parts {
		s := strings.Split(v, " ")
		theirMove, myMove := shape[s[0]], shape[s[1]]

		total += (get_shape_score(myMove) + get_outcome_score(theirMove, myMove))
	}

	fmt.Printf("Part 1: %v\n", total)

	total = 0

	for _, v := range parts {
		s := strings.Split(v, " ")
		theirMove, outcome := shape[s[0]], s[1]
		myMove := shape_to_pay(outcome, theirMove)

		total += (get_shape_score(myMove) + get_outcome_score(theirMove, myMove))
	}

	fmt.Printf("Part 2: %v\n", total)
}

func get_shape_score(shape string) int {
	switch shape {
	case "Rock":
		return 1
	case "Paper":
		return 2
	case "Scissors":
		return 3
	default:
		panic("Unknown shape")
	}
}

func get_outcome_score(theirMove string, myMove string) int {
	switch {
	case theirMove == myMove:
		return 3
	case theirMove == "Rock":
		if myMove == "Paper" {
			return 6
		} else {
			return 0
		}
	case theirMove == "Paper":
		if myMove == "Scissors" {
			return 6
		} else {
			return 0
		}
	case theirMove == "Scissors":
		if myMove == "Rock" {
			return 6
		} else {
			return 0
		}
	default:
		panic("unknown outcome")
	}
}

func shape_to_pay(outcome string, theirMove string) string {
	switch {
	case outcome == "Y": // draw
		return theirMove
	case outcome == "X": // lose
		if theirMove == "Rock" {
			return "Scissors"
		} else if theirMove == "Paper" {
			return "Rock"
		} else {
			return "Paper"
		}
	case outcome == "Z": // win
		if theirMove == "Rock" {
			return "Paper"
		} else if theirMove == "Paper" {
			return "Scissors"
		} else {
			return "Rock"
		}
	default:
		panic("unknown play")
	}
}
