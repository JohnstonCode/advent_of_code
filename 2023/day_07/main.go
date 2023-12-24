package main

import (
	"fmt"
	"os"
	"sort"
	"strconv"
	"strings"
)

var order = []string{"2", "3", "4", "5", "6", "7", "8", "9", "T", "J", "Q", "K", "A"}
var jokerOrder = []string{"J", "2", "3", "4", "5", "6", "7", "8", "9", "T", "Q", "K", "A"}

type Hand struct {
	hand  string
	bid   int
	hType int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	part1 := part1(string(content))
	part2 := part2(string(content))

	fmt.Printf("Part1: %s\n", part1)
	fmt.Printf("Part2: %s\n", part2)
}

func part1(input string) string {
	lines := strings.Split(input, "\n")
	var hands []Hand

	for _, line := range lines {
		parts := strings.Split(line, " ")
		n, _ := strconv.Atoi(parts[1])

		hands = append(hands, Hand{
			hand:  parts[0],
			bid:   n,
			hType: getHandTypeScore(parts[0], false),
		})
	}

	sort.Slice(hands, func(i, j int) bool {
		if hands[i].hType == hands[j].hType {
			a := hands[i]
			b := hands[j]

			for x := 0; x < len(hands[i].hand); x++ {
				if IndexOf(order, string(a.hand[x])) == IndexOf(order, string(b.hand[x])) {
					continue
				}

				return IndexOf(order, string(a.hand[x])) < IndexOf(order, string(b.hand[x]))
			}

			return true
		}

		return hands[i].hType < hands[j].hType
	})

	total := 0

	for i, h := range hands {
		total += h.bid * (i + 1)
	}

	return strconv.Itoa(total)
}

func part2(input string) string {
	lines := strings.Split(input, "\n")
	var hands []Hand

	for _, line := range lines {
		parts := strings.Split(line, " ")
		n, _ := strconv.Atoi(parts[1])

		hands = append(hands, Hand{
			hand:  parts[0],
			bid:   n,
			hType: getHandTypeScore(parts[0], true),
		})
	}

	sort.Slice(hands, func(i, j int) bool {
		if hands[i].hType == hands[j].hType {
			a := hands[i]
			b := hands[j]

			for x := 0; x < len(hands[i].hand); x++ {
				if IndexOf(jokerOrder, string(a.hand[x])) == IndexOf(jokerOrder, string(b.hand[x])) {
					continue
				}

				return IndexOf(jokerOrder, string(a.hand[x])) < IndexOf(jokerOrder, string(b.hand[x]))
			}

			return true
		}

		return hands[i].hType < hands[j].hType
	})

	total := 0

	for i, h := range hands {
		total += h.bid * (i + 1)
	}

	return strconv.Itoa(total)
}

func contains(m map[string]int, c int) bool {
	for _, v := range m {
		if v == c {
			return true
		}
	}

	return false
}

func IndexOf(slice []string, value string) int {
	for i := 0; i < len(slice); i++ {
		if slice[i] == value {
			return i
		}
	}

	return -1
}

func getHandTypeScore(hand string, joker bool) int {
	chars := make(map[string]int)

	for i := 0; i < len(hand); i++ {
		chars[string(hand[i])]++
	}

	if !strings.Contains(hand, "J") || !joker || hand == "JJJJJ" {
		return getTypeScore(chars)
	}

	maxVal := 0
	var maxKey string

	for key, v := range chars {
		if v > maxVal {
			maxKey = key
			maxVal = v
		}
	}

	if maxKey == "J" {
		return getTypeScore(chars)
	}

	chars[maxKey] += chars["J"]

	delete(chars, "J")

	return getTypeScore(chars)
}

func getTypeScore(c map[string]int) int {
	if len(c) == 1 {
		return 7
	} else if contains(c, 4) {
		return 6
	} else if contains(c, 3) && contains(c, 2) {
		return 5
	} else if contains(c, 3) {
		return 4
	} else if contains(c, 2) && len(c) == 3 {
		return 3
	} else if contains(c, 2) {
		return 2
	} else {
		return 1
	}
}
